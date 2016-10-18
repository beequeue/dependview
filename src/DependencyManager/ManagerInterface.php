<?php

namespace Beequeue\DependView\DependencyManager;

interface ManagerInterface
{
    public function getId(): string;

    public function getProjectDependencies(): array;
}