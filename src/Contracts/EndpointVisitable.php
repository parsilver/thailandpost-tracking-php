<?php

namespace Farzai\ThaiPost\Contracts;

interface EndpointVisitable
{
    public function accept(EndpointVisitor $visitor);
}
