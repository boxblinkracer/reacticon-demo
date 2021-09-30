<?php

namespace GoogleRecaptcha\Storefront;

use Shopware\Core\Checkout\Customer\SalesChannel\AbstractRegisterRoute;
use Shopware\Core\Checkout\Customer\SalesChannel\CustomerResponse;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
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
     * @param AbstractRegisterRoute $decorated
     */
    public function __construct(AbstractRegisterRoute $decorated)
    {
        $this->decorated = $decorated;
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

        $params = $data->all();

        if ($action !== self::CAPTCHA_ACTION) {
            $this->throwError('Invalid Action', $params);
        }

        if ($token !== 'abc') {
            $this->throwError('Invalid Token', $params);
        }

        return $this->decorated->register($data, $context, $validateStorefrontUrl, $additionalValidationDefinitions);
    }


    /**
     * @param string $message
     * @param array $data
     */
    private function throwError(string $message, array $data): void
    {
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
