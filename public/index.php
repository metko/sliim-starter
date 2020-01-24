<?php

declare(strict_types=1);

use App\ContainerFactory;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

// Set the default timezone.
date_default_timezone_set('Europe/Paris');

// Set the absolute path to the root directory.
$rootPath = realpath(__DIR__ . '/..');

// Include the composer autoloader.
include_once($rootPath . '/vendor/autoload.php');

// Create the container for dependency injection.
try {
    $container = ContainerFactory::create($rootPath);
} catch (Exception $e) {
    die($e->getMessage());
}

// Set the container to create the App with AppFactory.
AppFactory::setContainer($container);
$app = AppFactory::create();

// Set the cache file for the routes. Note that you have to delete this file
// whenever you change the routes.
$app->getRouteCollector()->setCacheFile(
    $rootPath . '/cache/routes.cache'
);

// Add the routing middleware.
$app->addRoutingMiddleware();

// Add the twig middleware (which when processed would set the 'view' to the container).
$app->add(
    new TwigMiddleware(
        new Twig(
            $rootPath . '/src/views',
            [
                'cache' => $rootPath . '/cache',
                'auto_reload' => true,
                'debug' => false,
            ]
        ),
        $container,
        $app->getRouteCollector()->getRouteParser(),
        $app->getBasePath()
    )
);

// Add error handling middleware.
$displayErrorDetails = true;
$logErrors = true;
$logErrorDetails = false;
$app->addErrorMiddleware($displayErrorDetails, $logErrors, $logErrorDetails);

// Define the app routes.
include_once($rootPath . '/application/routes.php');
// Run the app.
$app->run();
