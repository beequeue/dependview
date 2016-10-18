<?php

namespace Beequeue\DependView\DependencyManager\VersionExtractor;

class GitlabArchiveUrlExtractor implements ExtractorInterface
{
    public function extract(string $versionStr)
    {
        if (preg_match('/repository\/archive\.tar\.gz\?ref=(.+)$/', $versionStr, $matches)) {
            $version = $matches[1];
            return $version;
        }

        return false;
    }
}