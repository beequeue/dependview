<?php

namespace Beequeue\DependView\Project\Vcs;

interface VcsInterface
{
    public function __construct(array $options = []);

    public function updateCache();
}
