<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Webhook\Entity\HookDataEntity;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

class Postman
{

    /**
     * Get data from webhook
     * @link https://track.thailandpost.co.th/developerGuide (หมวด HookData)
     *
     * @return HookDataEntity
     */
    public static function capture()
    {
        $psr17Factory = new Psr17Factory();

        $creator = new ServerRequestCreator(
            $psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory
        );

        $serverRequest = $creator->fromGlobals();

        return HookDataEntity::fromRequest($serverRequest);
    }
}