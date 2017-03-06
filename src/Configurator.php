<?php

namespace Tourbillon\Configurator;

use Symfony\Component\Yaml\Yaml;

/**
 * Description of Configurator
 *
 * @author gjean
 */
class Configurator
{
    private static $instance;
    private $data;

    public function __construct($path)
    {
        $this->data = Yaml::parse(file_get_contents($path));
    }

    public function get($name)
    {
        if (!isset($this->data[$name])) {
            throw new \Symfony\Component\Yaml\Exception\RuntimeException("No parameters exist for {$name}");
        }

        return $this->data[$name];
    }

    public static function getInstance($path)
    {
        if (null === self::$instance) {
            self::$instance = new self($path);
        }
    }

}
