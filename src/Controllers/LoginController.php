<?php
namespace Danilo\EcommerceDesafio\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Danilo\EcommerceDesafio\Models\Cliente;
use Danilo\EcommerceDesafio\Views\RenderView;
use Firebase\JWT\JWT;
use Danilo\EcommerceDesafio\Config\TokenJwt;

class LoginController{
    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Autenticação"},
     *     summary="Realiza login e retorna um JWT Token",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados de login",
     *         @OA\JsonContent(
     *             required={"email", "senha"},
     *             @OA\Property(property="email", type="string", description="E-mail do usuário"),
     *             @OA\Property(property="senha", type="string", description="Senha do usuário"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", description="JWT Token para autenticação")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Falha no login",
     *         @OA\JsonContent(
     *             @OA\Property(property="mensagem", type="string", description="Mensagem de erro")
     *         )
     *     ),
     * )
     */
    public static function acao(Request $request, Response $response) {
        $response = $response->withHeader('Content-Type', 'application/json');

        $data = $request->getParsedBody();

        // TODO find na repo de adminsitrador

        if(! $data){
            $response->getBody()->write(json_encode([ "mensagem" => "Email ou senha inválido" ]));
            return $response->withStatus(400);
        }

        if( !isset($data['email']) || $data['email'] != "adm@teste.com" ){
            $response->getBody()->write(json_encode([ "mensagem" => "Email inválido" ]));
            return $response->withStatus(400);
        }

        if( !isset($data['senha']) || $data['senha'] != "123456") {
            $response->getBody()->write(json_encode([ "mensagem" => "Senha inválida" ]));
            return $response->withStatus(400);
        }

        $dado_do_token = array(
            "iat" => time(), // Tempo atual em segundos
            "nbf" => time(), // Token começa a ser válido agora
            "exp" => time() + (60 * 60), // Expira em 1 hora ( (60 * 60) = 3600 segundos)
            "emailLogado" => "adm@teste.com",
            "permissao" => [
                [ "controller" => "Clientes", "action" => "index" ],
                [ "controller" => "Clientes", "action" => "cadastrar" ],
                [ "controller" => "Clientes", "action" => "mostrar" ],
                [ "controller" => "Clientes", "action" => "criar" ],
                [ "controller" => "Clientes", "action" => "atualizar" ],
                [ "controller" => "Clientes", "action" => "excluir" ]
            ]
        );
        
        $jwt = JWT::encode($dado_do_token, TokenJwt::get());
        
        $response->getBody()->write(json_encode([ "token" => $jwt ]));
        
        return $response->withStatus(200);
    }
}