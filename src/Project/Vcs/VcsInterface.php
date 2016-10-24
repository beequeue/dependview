<?php

namespace Beequeue\DependView\Project\Vcs;

use GuzzleHttp\Client as HttpClient;

interface VcsInterface
{
    public function __construct(array $options = []);

    public function setHttpClient(HttpClient $client);

    public function updateCacheForFilePaths(array $filePaths);
}
