<?php
define('ENVIRONMENT', 'dev');

require 'protected/core/ajn.php';
require 'protected/vendor/autoload.php';
require 'protected/core/loader.php';


$app = new Ajn;
$app->dir(__DIR__);
$app->init();
$app->run();
