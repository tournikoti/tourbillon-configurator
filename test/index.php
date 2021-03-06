<?php

use Tourbillon\Configurator\ConfiguratorFactory;

require '../vendor/autoload.php';

$configurator = ConfiguratorFactory::createInstance('config/yaml/config.yml');

$configurator->importFile('config/yaml/test.yml');

$configurator->setParameters([
    "app.root_dir" => __DIR__ . "/../app",
    "app.src_dir" => "%app.root_dir%/../src"
]);

var_dump($configurator->getParameter('app.src_dir'));
