<?php

namespace Farzai\ThaiPost\Contracts;

use Farzai\ThaiPost\Transporter;

interface Endpoint
{
    /**
     * @return Transporter
     */
    public function getTransporter(): Transporter;
}