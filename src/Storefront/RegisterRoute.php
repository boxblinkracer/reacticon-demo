<?php

namespace GoogleRecaptcha\Storefront;

use GoogleRecaptcha\Components\GoogleRecaptcha\GoogleRecaptcha;
use GoogleRecaptcha\Services\HttpClient\HttpClient;
use Shopware\Core\Checkout\Customer\SalesChannel\AbstractRegisterRoute;
use Shopware\Core\Checkout\Customer\SalesChannel\CustomerResponse;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class RegisterRoute extends AbstractRegisterRoute
{

    public const CAPTCHA_ACTION = "register";

    /**
     * @var AbstractRegisterRoute
     */
    private $decorated;

    /**
     * @var GoogleRecaptcha
     */
    private $captcha;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;


    /**
     * @param AbstractRegisterRoute $decorated
     * @param SystemConfigService $config
     * @param FlashBagInterface $flashBag
     */
    public function __construct(AbstractRegisterRoute $decorated, SystemConfigService $config, FlashBagInterface $flashBag)
    {
        $this->decorated = $decorated;
        $this->flashBag = $flashBag;

        $this->captcha = $this->buildCaptcha($config);
    }


    /**
     * @return AbstractRegisterRoute
     */
    public function getDecorated(): AbstractRegisterRoute
    {
        return $this->decorated;
    }

    /**
     * @param RequestDataBag $data
     * @param SalesChannelContext $context
     * @param bool $validateStorefrontUrl
     * @param DataValidationDefinition|null $additionalValidationDefinitions
     * @return CustomerResponse
     */
    public function register(RequestDataBag $data, SalesChannelContext $context, bool $validateStorefrontUrl = true, ?DataValidationDefinition $additionalValidationDefinitions = null): CustomerResponse
    {
        $action = $data->get('captcha_action');
        $token = $data->get('captcha_token');


        if (!$this->captcha->verifyToken($action, $token)) {
            $this->throwError('Captcha validation failed! Are you a bot?', $data->all());
        }

        # continue with original registration
        return $this->decorated->register($data, $context, $validateStorefrontUrl, $additionalValidationDefinitions);
    }

    /**
     * @param SystemConfigService $config
     * @return GoogleRecaptcha
     */
    private function buildCaptcha(SystemConfigService $config): GoogleRecaptcha
    {
        $siteKey = (string)$config->get('GoogleRecaptchaPlugin.config.siteKey');
        $secret = (string)$config->get('GoogleRecaptchaPlugin.config.secretKey');
        $minScore = (float)$config->get('GoogleRecaptchaPlugin.config.registerMinScore');

        return new GoogleRecaptcha($siteKey, $secret, $minScore, new HttpClient());
    }

    /**
     * @param string $message
     * @param array $data
     */
    private function throwError(string $message, array $data): void
    {
        # we first need to create a flashbag message
        # because the message of the violation list would be displayed directly
        # next to the input field which is not set and hidden anyway.
        # so we show a flash message on top of the page
        $this->flashBag->add('danger', $message);

        # now also create a violation exception to
        # cancel the registration process.
        $violations = new ConstraintViolationList([]);

        $violation = new ConstraintViolation(
            $message,
            '',
            [],
            '',
            '',
            ''
        );

        $violations->add($violation);

        throw new ConstraintViolationException($violations, $data);
    }

}
