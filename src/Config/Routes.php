<?php
namespace Danilo\EcommerceDesafio\Config;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Danilo\EcommerceDesafio\Controllers\ClientesController;
use Danilo\EcommerceDesafio\Controllers\PedidosController;
use Danilo\EcommerceDesafio\Controllers\LoginController;
use Danilo\EcommerceDesafio\Controllers\HomeController;
use Danilo\EcommerceDesafio\Views\RenderView;
use Danilo\EcommerceDesafio\Middleware\AuthenticationMiddleware;

class Routes{
    public static function render($app){
        $authenticationMiddleware = new AuthenticationMiddleware();

        $app->get('/', [HomeController::class, 'index']);

        $app->get('/login', [LoginController::class, 'loginForm']);
        $app->post('/login', [LoginController::class, 'login']);
        $app->get('/sair', [LoginController::class, 'sair']);

        $app->group('', function($group) {
            $group->get('/clientes', [ClientesController::class, 'index']);
            $group->get('/clientes.json', [ClientesController::class, 'indexJson']);
            $group->get('/clientes/novo', [ClientesController::class, 'novo']);
            $group->get('/clientes/{id}/excluir', [ClientesController::class, 'excluir']);
            $group->post('/clientes', [ClientesController::class, 'criar']);
            $group->get('/clientes/{id}/editar', [ClientesController::class, 'editar']);
            $group->post('/clientes/{id}', [ClientesController::class, 'atualizar']);

            $group->get('/pedidos', [PedidosController::class, 'index']);
            $group->get('/pedidos/novo', [PedidosController::class, 'novo']);
            $group->get('/pedidos/{id}/excluir', [PedidosController::class, 'excluir']);
            $group->post('/pedidos', [PedidosController::class, 'criar']);
            $group->get('/pedidos/{id}/editar', [PedidosController::class, 'editar']);
            $group->post('/pedidos/{id}', [PedidosController::class, 'atualizar']);

        })->add($authenticationMiddleware);

        // meus arquivos de assets oficiais
        $app->get('/assets/{path:.*}', function ($request, $response, $args) {
            $file = __DIR__ . '/../Assets/' . $args['path'];
        
            if (!file_exists($file)) {
                return $response->withStatus(404);
            }

            $extensionMimeTypes = [
                'css' => 'text/css',
                'js'  => 'application/javascript',
            ];

            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $mime = $extensionMimeTypes[$extension] ?? mime_content_type($file);

            $stream = new \Slim\Psr7\Stream(fopen($file, 'r'));

            return $response->withHeader('Content-Type', $mime)->withBody($stream);
        });

        $errorMiddleware = $app->addErrorMiddleware(true, true, true);

        $errorMiddleware->setErrorHandler(
            HttpNotFoundException::class,
            function (ServerRequestInterface $request, \Throwable $exception, bool $displayErrorDetails) {
                return RenderView::render404(new Response());
            }
        );
    }
}