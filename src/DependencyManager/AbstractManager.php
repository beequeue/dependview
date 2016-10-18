<?php

namespace Beequeue\DependView\DependencyManager;

use Beequeue\DependView\DependencyManager\VersionExtractor\ExtractorInterface;

abstract class AbstractManager implements ManagerInterface
{
    protected $options = [];

    protected $projectCacheDir;

    protected $versionExtractors = [];

    public function __construct(array $options, string $projectCacheDir)
    {
        $this->options = $options;
        $this->projectCacheDir = $projectCacheDir;

        $this->setupVersionExtractors();
    }

    public function setupVersionExtractors()
    {
        // Override with manager-specific version string handling
    }

    public function addVersionExtractor(ExtractorInterface $extractor)
    {
        $this->versionExtractors[] = $extractor;
    }

    public function mapDependenciesToVersions(array $dependencies) : array
    {
        $mappedVersions = [];

        foreach ($dependencies as $name => $versionStr) {
            $mappedVersions[$name] = $this->extractVersion($versionStr);
        }

        return $mappedVersions;
    }

    public function extractVersion(string $versionStr) : string
    {
        foreach ($this->versionExtractors as $extractor) {
            $version = $extractor->extract($versionStr);
            if ($version) {
                return $version;
            }
        }

        return $versionStr;
    }
}