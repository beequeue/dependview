<?php

namespace Beequeue\DependView\Project;

use Beequeue\DependView\Project\Vcs\VcsInterface;
use Beequeue\DependView\DependencyManager\ManagerInterface;

class ProjectFactory
{
    protected $cacheDir;

    public function __construct(array $options = [])
    {
        $this->cacheDir = $options['cacheDir'];
    }

    public function create(array $projectConfig): ProjectInterface
    {
        $projectCacheDir = $this->generateProjectCacheDir($projectConfig);

        $dependencyManagers = [];
        if (!empty($projectConfig['dependency-managers'])) {
            foreach ($projectConfig['dependency-managers'] as $type => $managerConfig) {
                $dependencyManagers[$type] = $this->createDependencyManager(
                    $type, $managerConfig, $projectCacheDir
                );
            }
        }

        return new Project([
            'id' => $projectConfig['id'],
            'label' => $projectConfig['label'],
            'vcs' => $this->createVcs($projectConfig['vcs']),
            'dependency-managers' => $dependencyManagers,
            'cacheDir' => $projectCacheDir
        ]);
    }

    private function generateProjectCacheDir(array $projectConfig): string
    {
        return $this->cacheDir . '/' . $projectConfig['id'];
    }


    private function createVcs(array $vcsConfig): VcsInterface
    {
        if (empty($vcsConfig['type'])) {
            throw new \Exception('VCS type not set');
        }

        $vcsClassName = $this->getVcsClassNameFromType($vcsConfig['type']);

        if (!class_exists($vcsClassName)) {
            throw new \Exception(sprintf('VCS class "%s" not found', $vcsClassName));
        }

        $vcsObject = new $vcsClassName($vcsConfig);

        return $vcsObject;
    }

    private function getVcsClassNameFromType(string $vcsType): string
    {
        $typeParts = explode('-', $vcsType);
        $className = implode('', array_map('ucfirst', $typeParts));

        $fullyQualifiedClassName = sprintf(
            'Beequeue\DependView\Project\Vcs\%s',
            $className
        );

        return $fullyQualifiedClassName;
    }

    private function createDependencyManager(
        string $type,
        array $config,
        string $projectCacheDir
    ): ManagerInterface {
        if (empty($type)) {
            throw new \Exception('Dependency manager type not set');
        }

        $managerClassName = sprintf(
            'Beequeue\DependView\DependencyManager\%s',
            ucfirst($type)
        );

        if (!class_exists($managerClassName)) {
            throw new \Exception(sprintf(
                'DependencyManager class "%s" not found',
                $managerClassName
            ));
        }

        $managerObject = new $managerClassName($config, $projectCacheDir);

        return $managerObject;
    }
}