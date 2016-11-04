<?php

namespace Beequeue\DependView\DependencyManager;

use Beequeue\DependView\DependencyManager\VersionExtractor\GitlabArchiveUrlExtractor;

class Npm extends AbstractManager
{
    public function getId(): string
    {
        return 'npm';
    }

    public function getRequiredFilePaths(): array
    {
        return [
            'package.json'
        ];
    }

    public function setupVersionExtractors()
    {
        $this->addVersionExtractor(new GitlabArchiveUrlExtractor());
    }

    public function getProjectDependencies(): array
    {
        $packageData = $this->parsePackageJsonFile();

        $dependencies = [
            'dependencies' => [],
            'devDependencies' => []
        ];

        if (!empty($packageData['dependencies'])) {
            $dependencies['dependencies'] = $this->mapDependenciesToVersions(
                $packageData['dependencies']
            );
        }

        if (!empty($packageData['devDependencies'])) {
            $dependencies['devDependencies'] = $this->mapDependenciesToVersions(
                $packageData['devDependencies']
            );
        }

        return $dependencies;
    }

    private function parsePackageJsonFile(): array
    {
        $pathToFile = $this->projectCacheDir . '/package.json';

        if (!file_exists($pathToFile)) {
            return [];
        }

        $packageData = json_decode(file_get_contents($pathToFile), true);

        return $packageData;
    }
}