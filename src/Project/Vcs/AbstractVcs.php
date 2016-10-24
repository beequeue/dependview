<?php

namespace Beequeue\DependView\Project\Vcs;

use GuzzleHttp\Client as HttpClient;

abstract class AbstractVcs implements VcsInterface
{
    protected $options;

    protected $httpClient;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function setHttpClient(HttpClient $client)
    {
        $this->httpClient = $client;
    }
}
