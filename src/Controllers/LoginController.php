<?php
namespace Danilo\EcommerceDesafio\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Danilo\EcommerceDesafio\Models\Cliente;
use Danilo\EcommerceDesafio\Views\RenderView;

class LoginController{
    public static function loginForm(Request $request, Response $response) {
        return RenderView::render($response);
    }

    public static function login(Request $request, Response $response) {

        $data = $request->getParsedBody();

        if(!$data){
            return RenderView::render($response, ["erro" => "Email ou senha inválido"], "LoginForm");
        }

        $email = $data["email"];
        $senha = $data["senha"];

        if(!isset($email) || $email != "adm@teste.com"){
            return RenderView::render($response, ["erro" => "Email inválido"], "LoginForm");
        }

        if(!isset($senha) || $senha != "123456"){
            return RenderView::render($response, ["erro" => "Senha inválida"], "LoginForm");
        }

        $cookieValue = "true";
        $expiryTime = time() + (1 * 60 * 60); // 1 hora * 60 minutos * 60 segundos
        $cookieExpiryDate = gmdate('D, d M Y H:i:s T', $expiryTime); // Formatando a data no padrão GMT RFC 2822

        $response = $response->withHeader('Set-Cookie', 'logado=' . $cookieValue . '; expires=' . $cookieExpiryDate . '; Path=/; HttpOnly');

        return $response->withStatus(302)->withHeader('Location', '/clientes');
    }

    public static function sair(Request $request, Response $response) {
        $response = $response->withAddedHeader('Set-Cookie', 'logado=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT');
        return $response->withStatus(302)->withHeader('Location', '/login');
    }
    
}