<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Danilo\EcommerceDesafio\Config\Routes;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    
    return $response
           ->withHeader('Access-Control-Allow-Origin', '*') // Pode ser restrito a domínios específicos, se necessário
           ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
           ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


Routes::render($app);

$app->run();
