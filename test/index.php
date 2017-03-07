<?php

use Tourbillon\Configurator\ConfiguratorFactory;

require '../vendor/autoload.php';

$configurator = ConfiguratorFactory::createInstance('config/yaml/config.yml');

var_dump($configurator->get('parameters'));
var_dump($configurator->get('databases'));
