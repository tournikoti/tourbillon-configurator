<?php

namespace Tourbillon\Configurator;

use Symfony\Component\Yaml\Exception\RuntimeException;
use Symfony\Component\Yaml\Yaml;

/**
 * Description of Configurator
 *
 * @author gjean
 */
class Configurator
{
    private static $instance;
    private $parameters;
    private $data;

    public function __construct($path)
    {
        $data = Yaml::parse(file_get_contents($path));

        if (!isset($data['parameters'])) {
            throw new RuntimeException("No parameters exist");
        }

        $this->parameters = $data['parameters'];

        if (isset($data['imports'])) {
            $data = $this->import($data);
        }

        $this->data = $this->transform($data);
    }

    public function get($name)
    {
        if (!isset($this->data[$name])) {
            throw new RuntimeException("Configuration for {$name} does not exist");
        }

        return $this->data[$name];
    }

    public static function getInstance($path)
    {
        if (null === self::$instance) {
            self::$instance = new self($path);
        }
        return self::$instance;
    }

    private function import($data)
    {
        if (isset($data['imports'])) {
            foreach ($data['imports'] as $path) {
                $d    = Yaml::parse(file_get_contents($path));
                $data = array_merge($data, $this->import($d));
            }
        }
        return $data;
    }

    private function transform($data)
    {
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $data[$key] = $this->transform($val);
            } else {
                $data[$key] = preg_replace_callback('/%([^%]*)%/', array($this, 'replace'), $val);
            }
        }
        return $data;
    }

    private function replace($matches)
    {
        return $this->parameters[$matches[1]];
    }

}