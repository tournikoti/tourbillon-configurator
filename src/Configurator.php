<?php

namespace Tourbillon\Configurator;

use Symfony\Component\Yaml\Exception\RuntimeException;
use Symfony\Component\Yaml\Yaml;

/**
 * Description of Configurator
 *
 * @author gjean
 */
abstract class Configurator
{
    private static $instance;
    private $parameters = array();
    private $data = array();

    public function __construct($path)
    {
        $this->importFile($path);
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getParameter($name)
    {
        if (!array_key_exists($name, $this->parameters)) {
            throw new Exception("Parameter {$name} does not exist");
        }
        
        return $this->parameters[$name];
    }

    public function get($name)
    {
        if (!isset($this->data[$name])) {
            throw new RuntimeException("Configuration for {$name} does not exist");
        }

        return $this->data[$name];
    }

    public function importFile($path)
    {
        $data = $this->parse($path);

        if (isset($data['imports'])) {
            $data = $this->import(dirname($path), $data);
        }

        if (array_key_exists('parameters', $data)) {
            $this->parameters = array_replace_recursive($this->parameters, empty($data['parameters']) ? array() : $data['parameters']);
        }

        $this->data = array_replace_recursive($this->data, $this->transform($data));
    }

    private function import($directory, $data)
    {
        if (isset($data['imports'])) {
            foreach ($data['imports'] as $path) {

                $d    = $this->parse($directory . '/' . $path);
                $data = array_replace_recursive($data, $this->import($directory, $d));
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
        return array_key_exists($matches[1], $this->parameters)
            ? $this->parameters[$matches[1]]
            : $matches[0];
    }

    public static function getInstance($path)
    {
        if (null === self::$instance) {
            self::$instance = new self($path);
        }
        return self::$instance;
    }

    protected abstract function parse($path);
}
