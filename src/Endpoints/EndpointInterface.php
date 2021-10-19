<?php

namespace Farzai\ThaiPost\Endpoints;

use Farzai\ThaiPost\Transporter;

interface EndpointInterface
{
    /**
     * @return Transporter
     */
    public function getTransporter(): Transporter;
}