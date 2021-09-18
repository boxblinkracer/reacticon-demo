<?php

namespace GoogleRecaptcha\Validators;

use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidator;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;


class NewsletterFormValidator extends DataValidator
{

    public const CAPTCHA_ACTION = "newsletter";

    /**
     * @var DataValidator
     */
    private DataValidator $coreService;


    /**
     * @param DataValidator $decorated
     */
    public function __construct(DataValidator $decorated)
    {
        $this->coreService = $decorated;
    }

    /**
     * @param array $data
     * @param DataValidationDefinition $definition
     * @param string $path
     */
    public function validate(array $data, DataValidationDefinition $definition, string $path = ''): void
    {
        $this->coreService->validate($data, $definition, $path);


        $action = $data['captcha_action'];
        $token = $data['captcha_token'];

        if ($action !== self::CAPTCHA_ACTION) {
            $this->throwError('Invalid Action', $data);
        }

        if ($token !== 'abc') {
            $this->throwError('Invalid Token', $data);
        }
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
