<?php
namespace Danilo\EcommerceDesafio\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Danilo\EcommerceDesafio\Models\Cliente;
use Danilo\EcommerceDesafio\Views\RenderView;
use Danilo\EcommerceDesafio\Servicos\ClienteServico;
use Danilo\EcommerceDesafio\Repositorios\ClienteRepositorioIlluminate;
use Danilo\EcommerceDesafio\Servicos\ErrosDeValidacao\FormatoValidacao;
use Danilo\EcommerceDesafio\Servicos\ErrosDeValidacao\VazioValidacao;
use Illuminate\Database\UniqueConstraintViolationException;

class ClientesController{
    private static $service;

    private static function service() {
        if(!isset($service)) $service = new ClienteServico(new ClienteRepositorioIlluminate());
        return $service;
    }

    public static function index(Request $request, Response $response) {
        $response = $response->withHeader('Content-Type', 'application/json');
        
        $querystringParams = $request->getQueryParams();
        $pagina = $querystringParams["pagina"] ?? 1;
        $totalPagina = $querystringParams["totalPagina"] ?? 5;
        if($totalPagina > 20) $totalPagina = 20;
        
        $params = [];

        if(isset($querystringParams["nome"]))
            $params["nome"] = $querystringParams["nome"];
        
        if(isset($querystringParams["id"]))
            $params["id"] = $querystringParams["id"];

        $clientes = self::service()->buscar($params, $pagina, $totalPagina);
        $response->getBody()->write(json_encode($clientes));
        return $response->withStatus(200);
    }

    public static function mostrar(Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $cliente = self::service()->buscarPorId($id);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($cliente));
        return $response->withStatus(200);
    }

    public static function criar(Request $request, Response $response) {
        $response = $response->withHeader('Content-Type', 'application/json');
        
        $cliente = new Cliente();
        
        $data = $request->getParsedBody();

        $cliente = new Cliente(
            null,
            $data['nome'] ?? "",
            $data['telefone'] ?? "",
            $data['email'] ?? "",
            $data['endereco'] ?? ""
        );
        
        try{
            self::service()->salvar($cliente);
        }
        catch (VazioValidacao $err) {
            $response->getBody()->write(json_encode(["mensagem" => $err->getMessage()]));
            return $response->withStatus(400);
        } 
        catch (FormatoValidacao $err) {
            $response->getBody()->write(json_encode(["mensagem" => $err->getMessage()]));
            return $response->withStatus(400);
        } 
        catch (UniqueConstraintViolationException $err) {
            $response->getBody()->write(json_encode(["mensagem" => "Registro duplicado"]));
            return $response->withStatus(400);
        }
        catch (Exception $e) {
            $response->getBody()->write(json_encode(["mensagem" => "Erro genérico: {$e->getMessage()}"]));
            return $response->withStatus(400);
        }

        $response->getBody()->write(json_encode($cliente));
        return $response->withStatus(201);
    }

    public static function atualizar(Request $request, Response $response) {
        $response = $response->withHeader('Content-Type', 'application/json');

        $id = $request->getAttribute('id');
        $cliente = self::service()->buscarPorId($id);

        if($cliente->id == 0){
            $response->getBody()->write(json_encode(["mensagem" => "id: $id não foi encontrado"]));
            return $response->withStatus(404);
        }

        $data = $request->getParsedBody();

        $cliente->nome = $data['nome'];
        $cliente->email = $data['email'];
        $cliente->telefone = $data['telefone'];
        $cliente->endereco = $data['endereco'];

        try{
            self::service()->salvar($cliente);
        }
        catch (VazioValidacao $err) {
            $response->getBody()->write(json_encode(["mensagem" => $err->getMessage()]));
            return $response->withStatus(400);
        } 
        catch (FormatoValidacao $err) {
            $response->getBody()->write(json_encode(["mensagem" => $err->getMessage()]));
            return $response->withStatus(400);
        } 
        catch (UniqueConstraintViolationException $err) {
            $response->getBody()->write(json_encode(["mensagem" => "Registro duplicado"]));
            return $response->withStatus(400);
        }
        catch (Exception $e) {
            $response->getBody()->write(json_encode(["mensagem" => "Erro genérico: {$e->getMessage()}"]));
            return $response->withStatus(400);
        }

        $response->getBody()->write(json_encode($cliente));
        return $response->withStatus(200);
    }

    public static function excluir(Request $request, Response $response) {
        $response = $response->withHeader('Content-Type', 'application/json');
        
        $id = $request->getAttribute('id');

        $cliente = self::service()->buscarPorId($id);
        if($cliente->id == 0){
            $response->getBody()->write(json_encode(["mensagem" => "id: $id não foi encontrado"]));
            return $response->withStatus(404);
        }

        self::service()->excluirPorId($id);
        
        return $response->withStatus(204);
    }
}