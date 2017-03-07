<?php

namespace Tourbillon\Configurator\Driver;

use Nette\Neon\Neon;
use Tourbillon\Configurator\Configurator;

/**
 * Description of YamlConfigurator
 *
 * @author gjean
 */
class NeonConfigurator extends Configurator
{

    protected function parse($path)
    {
        return Neon::decode(file_get_contents($path));
    }

}
