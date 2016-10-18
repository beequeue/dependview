<?php

namespace Beequeue\DependView\DependencyManager;

abstract class AbstractManager implements ManagerInterface
{
    protected $options = [];

    protected $projectCacheDir;

    public function __construct(array $options, string $projectCacheDir)
    {
        $this->options = $options;
        $this->projectCacheDir = $projectCacheDir;
    }

}