<?php

namespace Beequeue\DependView;

class ConfigHelper
{
    const ENV_VAR_PATTERN = '/\$ENV\[([^]]+)\]/';

    private $config;

    public function __construct(string $config)
    {
        $this->config = $config;
    }

    public function substituteEnvironmentVars()
    {
        $this->config = preg_replace_callback(
            self::ENV_VAR_PATTERN,
            function($matches) {
                return getenv($matches[1]);
            },
            $this->config
        );

        return $this;
    }

    public function getConfig() : string
    {
        return $this->config;
    }
}