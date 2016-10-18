<?php

namespace Beequeue\DependView\DependencyManager;

class Composer extends AbstractManager
{
    public function getId(): string
    {
        return 'composer';
    }

    public function getProjectDependencies(): array
    {
        $composerData = $this->parseComposerJsonFile();

        $dependencies = [
            'require' => [],
            'require-dev' => []
        ];

        if (!empty($composerData['require'])) {
            $dependencies['require'] = $composerData['require'];
        }

        if (!empty($composerData['require-dev'])) {
            $dependencies['require-dev'] = $composerData['require-dev'];
        }

        return $dependencies;
    }

    private function parseComposerJsonFile(): array
    {
        $pathToFile = $this->projectCacheDir . '/composer.json';

        $composerData = json_decode(file_get_contents($pathToFile), true);

        return $composerData;
    }
}