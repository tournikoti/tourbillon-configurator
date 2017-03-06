<?php

use Tourbillon\Configurator\Configurator;

require '../vendor/autoload.php';

$configurator = Configurator::getInstance('config.yml');

$configurator->get('databases');