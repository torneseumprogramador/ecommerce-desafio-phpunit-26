<?php
namespace Danilo\EcommerceDesafio\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthenticationMiddleware {

    public function __invoke(Request $request, RequestHandler $handler): Response {
        $cookies = $request->getCookieParams();

        if (!isset($cookies['logado']) || $cookies['logado'] !== 'true') {
            return (new \Slim\Psr7\Response())->withStatus(302)->withHeader('Location', '/login');
        }

        return $handler->handle($request);
    }
}
