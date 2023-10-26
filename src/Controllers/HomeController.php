<?php
namespace Danilo\EcommerceDesafio\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Danilo\EcommerceDesafio\Models\Cliente;
use Danilo\EcommerceDesafio\Views\RenderView;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Home")
 */

class HomeController{
     /**
     * @OA\Get(
     *     path="/",
     *     summary="Página inicial da API",
     *     description="Retorna informações básicas sobre a API e os endpoints disponíveis.",
     *     @OA\Response(
     *         response=200,
     *         description="Operação bem-sucedida",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensagem", type="string", example="Bem vindo a API feita no Desafio de PHP"),
     *             @OA\Property(
     *                 property="endpoints",
     *                 type="object",
     *                 @OA\Property(property="Login", type="string", example="/login"),
     *                 @OA\Property(property="Clientes", type="string", example="/clientes"),
     *                 @OA\Property(property="Pedidos", type="string", example="/pedidos")
     *             )
     *         )
     *     )
     * )
     */
    public static function index(Request $request, Response $response) {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            "mensagem" => "Bem vindo a API feita no Desafio de PHP",
            "endpoints" => [
                "Login" => "/login",
                "Clientes" => "/clientes",
                "Pedidos" => "/pedidos",
                "Swagger" => "/swagger/index.html",
            ]
        ]));
        $response->withStatus(200);
        return $response;
    }
}