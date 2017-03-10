<?php

use Tourbillon\Configurator\ConfiguratorFactory;

require '../vendor/autoload.php';

$configurator = ConfiguratorFactory::createInstance('config/yaml/config.yml');

$configurator->importFile('config/yaml/test.yml');

var_dump($configurator);
