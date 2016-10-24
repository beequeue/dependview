<?php

namespace Beequeue\DependView\Project;

use Beequeue\DependView\Project\Vcs\VcsInterface;
use GuzzleHttp\Client as HttpClient;

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

        $this->cacheDir = $options['cacheDir'];
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

    public function getRequiredFilePaths(): array
    {
        $filePaths = [];

        foreach ($this->getDependencyManagers as $manager) {
            $filePaths += $manager->getRequiredFilePaths();
        }

        return $filePaths;
    }

    public function updateCache(HttpClient $client)
    {
        $this->vcs->setHttpClient($client);

        $filePaths = $this->getRequiredFilePaths();
        $fileData = $this->vcs->updateCacheForFilePaths($filePaths);

        $this->writeFilesToCache($fileData);
    }

    protected function writeFilesToCache(array $fileData)
    {
        foreach ($fileData as $path => $data) {
            $this->writeFileToCache($path, $data);
        }
    }

    protected function writeFileToCache(string $path, string $data)
    {
        $fileName = sprintf('%s/%s', $this->cacheDir, $path);
        file_put_contents($fileName, $data);
    }
}