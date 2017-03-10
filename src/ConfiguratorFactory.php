<?php

namespace Tourbillon\Configurator;

use Tourbillon\Configurator\Configurator;
use Exception;

/**
 * Description of Configurator
 *
 * @author gjean
 */
abstract class ConfiguratorFactory
{
    /**
     *
     * @param type $path
     * @return Configurator
     * @throws Exception
     */
    public static function createInstance($path)
    {
        $class = __NAMESPACE__ . '\\Driver\\' . ucfirst(strtolower(pathinfo($path, PATHINFO_EXTENSION))) . 'Configurator';
        if (!class_exists($class)) {
            throw new Exception("Configurator $class does not exist");
        }
        return new $class($path);
    }
}
