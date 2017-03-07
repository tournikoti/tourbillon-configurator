<?php

namespace Tourbillon\Configurator;

/**
 * Description of Configurator
 *
 * @author gjean
 */
abstract class ConfiguratorFactory
{
    public static function createInstance($path)
    {
        $class = __NAMESPACE__ . '\\Driver\\' . ucfirst(strtolower(pathinfo($path, PATHINFO_EXTENSION))) . 'Configurator';
        if (!class_exists($class)) {
            throw new Exception("Configurator $class does not exist");
        }
        return new $class($path);
    }
}
