<?php
namespace Danilo\EcommerceDesafio\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Danilo\EcommerceDesafio\Models\Cliente;
use Danilo\EcommerceDesafio\Views\RenderView;

class HomeController{
    public static function index(Request $request, Response $response) {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            "mensagem" => "Bem vindo a API feita no Desafio de PHP",
            "endpoints" => [
                "Login" => "/login",
                "Clientes" => "/clientes",
                "Pedidos" => "/pedidos",
            ]
        ]));
        $response->withStatus(200);
        return $response;
    }
}