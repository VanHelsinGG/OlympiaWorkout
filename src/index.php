<?php
namespace OlympiaWorkout;

use OlympiaWorkout\bootstrap\Router\Request;
use OlympiaWorkout\bootstrap\Router\Router;

require_once __DIR__ . "/../vendor/autoload.php";

require_once __DIR__ . "/Routes/routes.php";

$request = new Request();
Router::resolve($request);

resolver bug de router