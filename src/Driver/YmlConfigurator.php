<?php

namespace Tourbillon\Configurator\Driver;

use Symfony\Component\Yaml\Yaml;
use Tourbillon\Configurator\Configurator;

/**
 * Description of YamlConfigurator
 *
 * @author gjean
 */
class YmlConfigurator extends Configurator
{

    protected function parse($path)
    {
        return Yaml::parse(file_get_contents($path));
    }

}
