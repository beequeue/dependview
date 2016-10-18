<?php

namespace Beequeue\DependView\Test\DependencyManager;

use Beequeue\DependView\DependencyManager\Composer;
use org\bovigo\vfs\vfsStream;

class ComposerTest extends DependencyManagerTestBase
{
    protected $composer;

    public function setUp()
    {
        $this->setUpProjectCacheDir('project-a');
        $this->composer = new Composer([], vfsStream::url('root'));
    }

    public function testGetProjectDependencies()
    {
        $expected = [
            "require-dev" => [
                "phpunit/phpunit" => "^5.5",
                "mikey179/vfsStream" => "^1.6"
            ],
            "require" => [
                "php" => ">=7.0",
                "silex/silex" => "^2.0",
                "twig/twig" => "^1.25",
                "symfony/yaml" => "^3.1"
            ]
        ];

        $actual = $this->composer->getProjectDependencies();

        $this->assertEquals($expected, $actual);
    }

}