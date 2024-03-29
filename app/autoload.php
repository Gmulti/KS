<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

// AnnotationDriver::registerAnnotationClasses();

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));


return $loader;
