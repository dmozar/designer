<?php session_start();


$start_memory = memory_get_usage();
global $start_memory;

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

define('ENV','development');

define('APP_SECRET_KEY', '-RealEstate8*X-Imeda-0-1');

define('PATH_ROOT',getcwd() . '/');
define('PATH_PUBLIC', __DIR__ . '/');
define('PATH_APPLICATION',PATH_ROOT . 'module/Application/');


include __DIR__ . '/../vendor/autoload.php';


Minty\Application::Start();
