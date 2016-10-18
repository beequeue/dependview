<?php

namespace Beequeue\DependView\DependencyManager\VersionExtractor;

interface ExtractorInterface
{
    public function extract(string $versionStr);
}