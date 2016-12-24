<?php

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once(__DIR__ . '/../vendor/autoload.php');
} elseif (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once(__DIR__ . '/../../vendor/autoload.php');
} else {
    throw new \Exception('autoload file not found');
}
use IVIR3aM\ObjectArrayTools\AbstractActiveArray;

class ActiveArray extends AbstractActiveArray {}

$object = new ActiveArray(['How', 'Are', 'You']);

print_r($object->getData());

$object->sort();

print_r($object->getData());exit;