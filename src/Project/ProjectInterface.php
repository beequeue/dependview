<?php

namespace Beequeue\DependView\Project;

interface ProjectInterface
{
    public function getId(): string;

    public function getLabel(): string;

    public function updateCache();

    public function getDependencyManagers(): array;
}