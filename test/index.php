<?php

use Tourbillon\Configurator\Configurator;

require '../vendor/autoload.php';

$configurator = Configurator::getInstance('config.yml');

var_dump($configurator->get('parameters'));
var_dump($configurator->get('databases'));
