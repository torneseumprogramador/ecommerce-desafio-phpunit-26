<?php
namespace Danilo\EcommerceDesafio\Config;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Danilo\EcommerceDesafio\Controllers\ClientesController;
use Danilo\EcommerceDesafio\Controllers\PedidosController;
use Danilo\EcommerceDesafio\Controllers\HomeController;
use Danilo\EcommerceDesafio\Controllers\LoginController;
use Danilo\EcommerceDesafio\Views\RenderView;

class Routes{
    public static function render($app, $checkUserMiddleware){
        $app->get('/', [HomeController::class, 'index'])->add($checkUserMiddleware);

        $app->post('/login', [LoginController::class, 'acao'])->add($checkUserMiddleware);

        $app->get('/clientes', [ClientesController::class, 'index'])->add($checkUserMiddleware);
        $app->post('/clientes', [ClientesController::class, 'criar'])->add($checkUserMiddleware);
        $app->delete('/clientes/{id}', [ClientesController::class, 'excluir'])->add($checkUserMiddleware);
        $app->get('/clientes/{id}', [ClientesController::class, 'mostrar'])->add($checkUserMiddleware);
        $app->put('/clientes/{id}', [ClientesController::class, 'atualizar'])->add($checkUserMiddleware);

        // TODO queridos alunos, fazer a questão abaixo sobre os pedidos
        $app->get('/pedidos', [PedidosController::class, 'index'])->add($checkUserMiddleware);
        $app->get('/pedidos/novo', [PedidosController::class, 'novo'])->add($checkUserMiddleware);
        $app->get('/pedidos/{id}/excluir', [PedidosController::class, 'excluir'])->add($checkUserMiddleware);
        $app->post('/pedidos', [PedidosController::class, 'criar'])->add($checkUserMiddleware);
        $app->get('/pedidos/{id}/editar', [PedidosController::class, 'editar'])->add($checkUserMiddleware);
        $app->post('/pedidos/{id}', [PedidosController::class, 'atualizar'])->add($checkUserMiddleware);

        // pagina não encontrada
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