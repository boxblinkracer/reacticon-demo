<?php

namespace GoogleRecaptcha\Subscriber\Storefront;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Event\StorefrontRenderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Subscriber implements EventSubscriberInterface
{

    /**
     * @var SystemConfigService
     */
    private $systemConfigService;


    /**
     * @param SystemConfigService $systemConfigService
     */
    public function __construct(SystemConfigService $systemConfigService)
    {
        $this->systemConfigService = $systemConfigService;
    }


    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            StorefrontRenderEvent::class => 'onStorefrontRender',
        ];
    }


    /**
     * @param StorefrontRenderEvent $event
     */
    public function onStorefrontRender(StorefrontRenderEvent $event)
    {
        $siteKey = $this->systemConfigService->get('GoogleRecaptchaPlugin.config.siteKey');

        $data = [
            'sitekey' => $siteKey,
        ];

        $event->setParameter('captcha', $data);
    }

}
