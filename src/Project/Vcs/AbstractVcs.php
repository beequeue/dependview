<?php

namespace Beequeue\DependView\Project\Vcs;

abstract class AbstractVcs implements VcsInterface
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function updateCache()
    {
        throw new \Exception('Descendents of AbstractVcs must implement updateCache');
    }
}
