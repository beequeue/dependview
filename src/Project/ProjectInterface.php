<?php

namespace Beequeue\DependView\Project;

use GuzzleHttp\Client as HttpClient;

interface ProjectInterface
{
    public function getId(): string;

    public function getLabel(): string;

    public function updateCache(HttpClient $client);

    public function getDependencyManagers(): array;
}