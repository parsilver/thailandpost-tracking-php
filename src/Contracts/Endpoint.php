<?php

namespace Farzai\ThaiPost\Contracts;

use Farzai\ThaiPost\Transporter;

interface Endpoint
{
    /**
     * Get current trasporter.
     * 
     * @return Transporter
     */
    public function getTransporter(): Transporter;
}
