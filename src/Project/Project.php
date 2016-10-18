<?php

namespace Beequeue\DependView\Project;

use Beequeue\DependView\Project\Vcs\VcsInterface;

class Project implements ProjectInterface
{
    protected $id;
    protected $label;
    protected $vcs;
    protected $dependencyManagers = [];

    protected $cacheDir;

    public function __construct($options = [])
    {
        $this->id = $options['id'];
        $this->label = $options['label'];

        $this->setVcs($options['vcs']);
        $this->dependencyManagers = $options['dependency-managers'];
    }

    protected function setVcs(VcsInterface $vcs)
    {
        $this->vcs = $vcs;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDependencyManagers(): array
    {
        return $this->dependencyManagers;
    }

    public function updateCache()
    {
        $this->vcs->updateCache();
    }
}