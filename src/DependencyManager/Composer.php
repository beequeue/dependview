<?php

namespace Beequeue\DependView\DependencyManager;

class Composer extends AbstractManager
{
    public function getId(): string
    {
        return 'composer';
    }

    public function getRequiredFilePaths(): array
    {
        return [
            'composer.json'
        ];
    }

    public function getProjectDependencies(): array
    {
        $composerData = $this->parseComposerJsonFile();

        $dependencies = [
            'require' => [],
            'require-dev' => []
        ];

        if (!empty($composerData['require'])) {
            $dependencies['require'] = $this->mapDependenciesToVersions(
                $composerData['require']
            );
        }

        if (!empty($composerData['require-dev'])) {
            $dependencies['require-dev'] = $this->mapDependenciesToVersions(
                $composerData['require-dev']
            );
        }

        return $dependencies;
    }

    private function parseComposerJsonFile(): array
    {
        $pathToFile = $this->projectCacheDir . '/composer.json';

        if (!file_exists($pathToFile)) {
            return [];
        }

        $composerData = json_decode(file_get_contents($pathToFile), true);

        return $composerData;
    }
}