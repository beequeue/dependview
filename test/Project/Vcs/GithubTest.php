<?php

namespace Beequeue\DependView\Test\Project\Vcs;

use Beequeue\DependView\Project\Vcs\Github;

class GithubTest extends VcsTestBase
{
    public function testCorrectlyRetrievesFiles()
    {
        $github = new Github([
            'url' => 'https://github.com/company/project',
            'branch' => 'master'
        ]);

        $expectedBaseUrl = 'https://raw.githubusercontent.com/company/project/master/';

        $httpClient = $this->getConfiguredHttpClientMock([
            [
                'method' => 'GET',
                'url' => $expectedBaseUrl . 'path1.ext',
                'responseBody' => 'First Body'
            ],
            [
                'method' => 'GET',
                'url' => $expectedBaseUrl . 'dir/path2.ext',
                'responseBody' => 'Second Body'
            ]
        ]);

        $github->setHttpClient($httpClient);

        $actual = $github->updateCacheForFilePaths(['path1.ext', 'dir/path2.ext']);

        $expected = [
            'path1.ext' => 'First Body',
            'dir/path2.ext' => 'Second Body'
        ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Failed to extractRepoPath from "https://github.com/invalid-repo"
     */
    public function testExceptionOnBadRepoPath()
    {
        $github = new Github([
            'url' => 'https://github.com/invalid-repo',
            'branch' => 'master'
        ]);

        $github->updateCacheForFilePaths(['someFile.ext']);
    }
}