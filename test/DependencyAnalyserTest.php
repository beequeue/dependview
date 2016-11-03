<?php

namespace Beequeue\DependView\Test;

use Beequeue\DependView\DependencyAnalyser;
use Beequeue\DependView\Project\ProjectInterface;
use Symfony\Component\Yaml\Yaml;
use org\bovigo\vfs\vfsStream;

class DependencyAnalyserTest extends \PHPUnit_Framework_TestCase
{
    public function testParseProjects()
    {
        $dependencyAnalyser = $this->getDependencyAnalyser();

        $projects = $dependencyAnalyser->getProjects();

        $this->assertCount(3, $projects);

        $projectInterface = 'Beequeue\DependView\Project\ProjectInterface';
        $this->assertInstanceOf($projectInterface, $projects['project-a']);
        $this->assertInstanceOf($projectInterface, $projects['project-b']);
        $this->assertInstanceOf($projectInterface, $projects['project-c']);
    }

    public function testAnalyse()
    {
        $dependencyAnalyser = $this->getDependencyAnalyser();

        $dependencyAnalyser->analyse();
    }

    private function getDependencyAnalyser()
    {
        $cacheFixturePath = sprintf('%s/_fixtures/cache', __DIR__);
        vfsStream::setup();
        vfsStream::copyFromFileSystem($cacheFixturePath);

        $dependencyAnalyser = new DependencyAnalyser([
            'projects' => Yaml::parse($this->getFixture('projects.yml')),
            'cacheDir' => vfsStream::url('root')
        ]);

        return $dependencyAnalyser;
    }

    private function getFixture($filename)
    {
        return file_get_contents(sprintf('%s/_fixtures/%s', __DIR__, $filename));
    }
}