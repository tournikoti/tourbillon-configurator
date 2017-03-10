<?php

namespace Tourbillon\Configurator;

use Exception;

/**
 * Description of Configurator
 *
 * @author gjean
 */
abstract class Configurator
{
    private static $instance;
    private $parameters = array();
    private $data       = array();
    private $level      = 0;

    public function __construct($path)
    {
        $this->importFile($path);
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setParameters($array)
    {
        $result = array();

        foreach($array as $path => $value) {
            $temp = &$result;

            foreach(explode('.', $path) as $key) {
                $temp =& $temp[$key];
            }
            $temp = $value;
        }

        $this->parameters = array_replace_recursive($this->parameters, $result);
        $this->parameters = $this->transform($this->parameters);
    }

    public function getParameter($name)
    {
        $temp = $this->parameters;
        foreach(explode('.', $name) as $key => $n) {
            if (!array_key_exists($n, $temp)) {
                throw new Exception("Parameter {$name} does not exist");
            }

            $temp = &$temp[$n];
        }

        return $temp;
    }

    public function get($name)
    {
        if (!isset($this->data[$name])) {
            throw new Exception("Configuration for {$name} does not exist");
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

                $d    = $this->parse($directory.'/'.$path);
                $data = array_replace_recursive($data, $this->import($directory, $d));
            }
        }
        return $data;
    }

    private function transform($data)
    {
        foreach ($data as $key => $val) {

            if ($this->level === 0 && (null === $val || empty($val))) {
                $val = array();
            }

            if (is_array($val)) {
                $this->levelUp();
                $data[$key] = $this->transform($val);
                $this->levelDown();
            } else {
                $data[$key] = preg_replace_callback('/%([^%]*)%/', array($this, 'replace'), $val);
            }
        }
        return $data;
    }
    
    private function replace($matches)
    {
        return $this->getParameter($matches[1]);
    }

    public static function getInstance($path)
    {
        if (null === self::$instance) {
            self::$instance = new self($path);
        }
        return self::$instance;
    }

    private function levelUp()
    {
        $this->level++;
    }

    private function levelDown()
    {
        $this->level--;
    }

    protected abstract function parse($path);
}