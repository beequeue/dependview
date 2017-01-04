<?php

namespace Beequeue\DependView\Test;

use Beequeue\DependView\ConfigHelper;

class ConfigHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testSubstituteEnvironmentVars()
    {
        $config = 'Line1: $ENV[DEPENDVIEW_1]' . PHP_EOL
                . 'Line2:' . PHP_EOL
                . 'Line3: This is the "$ENV[DEPENDVIEW_2]" variable';

        $expected = 'Line1: First' . PHP_EOL
                  . 'Line2:' . PHP_EOL
                  . 'Line3: This is the "Second" variable';

        $configHelper = new ConfigHelper($config);
        $actual = $configHelper->substituteEnvironmentVars()->getConfig();

        $this->assertEquals($expected, $actual);
    }

}