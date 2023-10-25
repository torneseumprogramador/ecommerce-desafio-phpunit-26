<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Danilo\EcommerceDesafio\Config\Routes;
use Tuupola\Middleware\HttpBasicAuthentication;
use Danilo\EcommerceDesafio\Config\TokenJwt;
use Danilo\EcommerceDesafio\PermissaoAutenticacao\Verificar;
use Firebase\JWT\JWT;
use Slim\Routing\RouteContext;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

// Token jwt
$app->add(new \Tuupola\Middleware\JwtAuthentication([
    "secure" => true, // Protege todas as rotas
    "ignore" => [
        "^/$", // Libera a rota da home
        "^/login$" // Libera a rota de login
    ],
    "attribute" => "decoded_token_data",
    "secret" => TokenJwt::get(),
    "algorithm" => ["HS256"],
    "secure" => false,
    "relaxed" => ["localhost"],
    "error" => function ($response, $arguments) {
        $data["mensagem"] = "API precisa de autenticação - {$arguments["message"]}";
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        return $response;
    }
]));

// autorização sobre rotas
$checkUserMiddleware = function ($request, $handler) {
     $routeContext = \Slim\Routing\RouteContext::fromRequest($request);
     $route = $routeContext->getRoute();
 
     // Se não há rota correspondente, retorne diretamente o request
     if (empty($route)) {
         return $handler->handle($request);
     }
 
     // Obtenha o nome da classe e da ação/método
     $callable = $route->getCallable();
     if (is_array($callable)) {
         [$controller, $action] = $callable;
 
         $controller = str_replace("Controller", "", $controller);
         $parts = explode("\\", $controller);
         $controller = end($parts);

         $request = $request->withAttribute('controller', $controller)->withAttribute('action', $action);
     }

    $headerValue = $request->getHeaderLine('Authorization');
    if($headerValue){
        $response = new \Slim\Psr7\Response(); // Crie uma nova resposta
        $response = $response->withHeader('Content-Type', 'application/json');

        list($bearer, $jwtToken) = explode(" ", $headerValue);

        if($jwtToken) {
            try {
                $key = TokenJwt::get();  // Sua chave secreta
                $decodedTokenData = JWT::decode($jwtToken, $key, ['HS256']);

                if(isset($decodedTokenData)){

                    $emailLogado = $decodedTokenData->emailLogado;
                    $permissoes = $decodedTokenData->permissao;
            
                    if( ! Verificar::permissionamento($request, $permissoes) ) {
                        $response->getBody()->write(json_encode(["mensagem" => "Usuário sem permissão para acessar esta área"]));
                        return $response->withStatus(403);
                    }
                }

            } catch (\Firebase\JWT\ExpiredException $e) {
                $response->getBody()->write(json_encode(["mensagem" => "Token expirou!"]));
                return $response->withStatus(403);
            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                $response->getBody()->write(json_encode(["mensagem" => "Token inválido!"]));
                return $response->withStatus(403);
            } catch (\Exception $e) {
                $response->getBody()->write(json_encode(["mensagem" => "Erro ao decodificar token: " . $e->getMessage()]));
                return $response->withStatus(403);
            }
        }
    }

    return $handler->handle($request); // Use o manipulador em vez do `$next`
};

Routes::render($app, $checkUserMiddleware);

// Cross Domain
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    
    return $response
           ->withHeader('Access-Control-Allow-Origin', '*') // Pode ser restrito a domínios específicos, se necessário
           ->withHeader('Access-Control-Allow-Headers', '*')
           ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->run();
