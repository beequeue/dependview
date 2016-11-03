<?php

namespace Beequeue\DependView\Test\Project\Vcs;

use Beequeue\DependView\Project\Vcs\GitlabApi;

class GitlabApiTest extends VcsTestBase
{
    public function testCorrectlyRetrievesFiles()
    {
        // @todo Much more, including validating private token header is sent

        $gitlabApi = new GitlabApi([
            'url' => 'https://my.gitlab.com/company/project',
            'branch' => 'master',
            'api-project-id' => 1234,
            'api-token' => 'abcdefg1234567'
        ]);

        $expectedBaseUrl = 'https://my.gitlab.com/api/v3/projects/1234/repository/files';

        $httpClient = $this->getConfiguredHttpClientMock([
            [
                'method' => 'GET',
                'url' => $expectedBaseUrl . '?file_path=path1.ext&ref=master',
                'responseBody' => $this->getMockResponseBody('path1.ext', 'First Body')
            ],
            [
                'method' => 'GET',
                'url' => $expectedBaseUrl . '?file_path=dir/path2.ext&ref=master',
                'responseBody' => $this->getMockResponseBody('dir/path2.ext', 'Second Body')
            ]
        ]);

        $gitlabApi->setHttpClient($httpClient);

        $actual = $gitlabApi->updateCacheForFilePaths(['path1.ext', 'dir/path2.ext']);

        $expected = [
            'path1.ext' => 'First Body',
            'dir/path2.ext' => 'Second Body'
        ];

        $this->assertEquals($expected, $actual);
    }

    protected function getMockResponseBody($file, $data)
    {
        return sprintf(
            '{"file_name":"%s","file_path":"%s","size":18,"encoding":"base64","content":"%s","last_commit_id":"050ff21ba01cbcaecd6d5218c29c18d5727c0b91"}',
            $file, $file,
            base64_encode($data)
        );
    }
}
