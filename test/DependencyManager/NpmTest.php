<?php

namespace Beequeue\DependView\Test\DependencyManager;

use Beequeue\DependView\DependencyManager\Npm;
use org\bovigo\vfs\vfsStream;

class NpmTest extends DependencyManagerTestBase
{
    protected $npm;

    public function setUp()
    {
        $this->setUpProjectCacheDir('project-b');
        $this->npm = new Npm([], vfsStream::url('root'));
    }

    public function testGetProjectDependencies()
    {
        $expected = [
            "dependencies" => [
                "express" => "^4.9.7",
                "socket.io" => "^1.2.0",
                "uranus" => "v1.1.0"
            ],
            "devDependencies" => [
                "gulp" => "^3.8.10",
                "gulp-file" => "^0.1.0",
                "mocha" => "^3.0.2"
            ]
        ];

        $actual = $this->npm->getProjectDependencies();

        $this->assertEquals($expected, $actual);
    }

}