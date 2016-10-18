<?php

namespace Beequeue\DependView\Test\DependencyManager\VersionExtractor;

use Beequeue\DependView\DependencyManager\VersionExtractor\GitlabArchiveUrlExtractor;

class GitlabArchiveUrlExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtract()
    {
        $extractor = new GitlabArchiveUrlExtractor();
        $versionStr = 'https://git.repo.com/planets/uranus/repository/archive.tar.gz?ref=v1.1.0';

        $this->assertSame('v1.1.0', $extractor->extract($versionStr));
    }
}