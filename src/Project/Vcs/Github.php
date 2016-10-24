<?php

namespace Beequeue\DependView\Project\Vcs;

class Github extends AbstractVcs
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

        $request = $this->httpClient->request('GET', $url);

        return $request->getBody();
    }

    public function buildUrlForPath(string $path)
    {
        $url = sprintf(
            "https://raw.githubusercontent.com/%s/%s/%s",
            $this->extractRepoPath(),
            $this->options['branch'],
            $path
        );

        return $url;
    }

    protected function extractRepoPath(): string
    {
        if (preg_match("#github\.com/([^/]+/[^/]+)#", $this->options['url'], $matches)) {
            return $matches[1];
        } else {
            throw new \Exception(sprintf(
                'Failed to extractRepoPath from "%s"',
                $this->options['url']
            ));
        }

    }
}