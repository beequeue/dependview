<?php

namespace Beequeue\DependView\Project\Vcs;

class GitlabApi extends AbstractVcs
{
    public function updateCacheForFilePaths(array $filePaths)
    {
        $fileData = [];

        foreach ($filePaths as $path) {
            $fileData[$path] = $this->getForPath($path);
        }

        return $fileData;
    }

    public function getForPath(string $path)
    {
        $url = $this->buildUrlForPath($path);

        $request = $this->httpClient->request('GET', $url, [
            'headers' => [
                'PRIVATE-TOKEN' => $this->options['api-token']
            ]
        ]);

        return $this->getFileFromResponseBody($request->getBody());
    }

    public function buildUrlForPath(string $path)
    {
        $url = sprintf(
            "%s/api/v3/projects/%d/repository/files?file_path=%s&ref=%s",
            $this->getApiBaseUrl(),
            $this->options['api-project-id'],
            $path,
            $this->options['branch']
        );

        return $url;
    }

    protected function getApiBaseUrl()
    {
        if (!preg_match('#^https?://[^/]+#', $this->options['url'], $match)) {
            throw new \Exception(sprintf(
                'Failed getApiBaseUrl for "%s"',
                $this->options['url']
            ));
        }

        return $match[0];
    }

    protected function getFileFromResponseBody(string $body)
    {
        $fileData = json_decode($body, true);

        if (!isset($fileData['content'])) {
            throw new \Exception("'content' field missing in response");
        }

        $fileContents = base64_decode($fileData['content']);

        if (false === $fileContents) {
            throw new \Exception("Error base64 decoding file contents");
        }

        return $fileContents;
    }
}