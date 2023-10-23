<?php
namespace Danilo\EcommerceDesafio\Config;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Danilo\EcommerceDesafio\Controllers\ClientesController;
use Danilo\EcommerceDesafio\Controllers\PedidosController;
use Danilo\EcommerceDesafio\Controllers\HomeController;
use Danilo\EcommerceDesafio\Views\RenderView;

class Routes{
    public static function render($app){
        $app->get('/', [HomeController::class, 'index']);

        $app->get('/clientes', [ClientesController::class, 'index']);
        $app->post('/clientes', [ClientesController::class, 'criar']);
        $app->delete('/clientes/{id}', [ClientesController::class, 'excluir']);
        $app->get('/clientes/{id}', [ClientesController::class, 'mostrar']);
        $app->put('/clientes/{id}', [ClientesController::class, 'atualizar']);

        // TODO queridos alunos, fazer a questão abaixo sobre os pedidos
        $app->get('/pedidos', [PedidosController::class, 'index']);
        $app->get('/pedidos/novo', [PedidosController::class, 'novo']);
        $app->get('/pedidos/{id}/excluir', [PedidosController::class, 'excluir']);
        $app->post('/pedidos', [PedidosController::class, 'criar']);
        $app->get('/pedidos/{id}/editar', [PedidosController::class, 'editar']);
        $app->post('/pedidos/{id}', [PedidosController::class, 'atualizar']);



        $errorMiddleware = $app->addErrorMiddleware(true, true, true);
        $errorMiddleware->setErrorHandler(
            HttpNotFoundException::class,
            function (ServerRequestInterface $request, \Throwable $exception, bool $displayErrorDetails) {
                $response = new Response();
                $response = $response->withHeader('Content-Type', 'application/json');
                $response->getBody()->write(json_encode(["mensagem" => "O endpoint que você procura não existe"]));
                $response->withStatus(404);
                return $response;
            }
        );
    }
}