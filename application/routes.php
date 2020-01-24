<?php

use App\Controllers\HomeController;
use App\Controllers\HelloController;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\ExceptionDemoController;

$app->group('/', function (RouteCollectorProxy $group) {

   $group->get('', HomeController::class)->setName('home');
   $group->get('hello/{name}', HelloController::class)->setName('hello');
   $group->get('exception-demo', ExceptionDemoController::class)->setName('exception-demo');
   
});