<?php

namespace Beequeue\DependView\DependencyManager;

class Npm extends AbstractManager
{
    public function getId(): string
    {
        return 'npm';
    }

    public function getProjectDependencies(): array
    {
        $packageData = $this->parsePackageJsonFile();

        $dependencies = [
            'dependencies' => [],
            'devDependencies' => []
        ];

        if (!empty($packageData['dependencies'])) {
            $dependencies['dependencies'] = $packageData['dependencies'];
        }

        if (!empty($packageData['devDependencies'])) {
            $dependencies['devDependencies'] = $packageData['devDependencies'];
        }

        return $dependencies;
    }

    private function parsePackageJsonFile(): array
    {
        $pathToFile = $this->projectCacheDir . '/package.json';

        $packageData = json_decode(file_get_contents($pathToFile), true);

        return $packageData;
    }
}