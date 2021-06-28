<?php namespace Farzai\ThaiPost\Client;

interface Factory
{
    /**
     * @return HttpClient
     */
    public function createClient(): HttpClient;
}