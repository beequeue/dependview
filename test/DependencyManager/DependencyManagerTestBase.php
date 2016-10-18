<?php

namespace Beequeue\DependView\Test\DependencyManager;

use org\bovigo\vfs\vfsStream;

class DependencyManagerTestBase extends \PHPUnit_Framework_TestCase
{
    protected function setUpProjectCacheDir(string $projectId)
    {
        $fixturePath = sprintf('%s/../_fixtures/cache/%s', __DIR__, $projectId);
        vfsStream::copyFromFileSystem($fixturePath);
    }
}
