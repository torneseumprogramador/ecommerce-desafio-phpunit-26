<?php
namespace Danilo\EcommerceDesafio\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Danilo\EcommerceDesafio\Models\Pedido;
use Danilo\EcommerceDesafio\Views\RenderView;
use Danilo\EcommerceDesafio\Servicos\PedidoServico;
use Danilo\EcommerceDesafio\Repositorios\PedidoRepositorioIlluminate;
use Danilo\EcommerceDesafio\Servicos\ErrosDeValidacao\FormatoValidacao;
use Danilo\EcommerceDesafio\Servicos\ErrosDeValidacao\VazioValidacao;
use Illuminate\Database\UniqueConstraintViolationException;

class PedidosController{
    private static $service;

    private static function service() {
        if(!isset($service)) $service = new PedidoServico(new PedidoRepositorioIlluminate());
        return $service;
    }

    public static function index(Request $request, Response $response) {
        $pedidos = self::service()->buscar();
        return RenderView::render($response, ['pedidos' => $pedidos]);
    }

    public static function novo(Request $request, Response $response) {
        return RenderView::render($response, [], "Form");
    }

    public static function criar(Request $request, Response $response) {
        $pedido = new Pedido();
        
        $data = $request->getParsedBody();

        $pedido = new Pedido(
            null,
            $data['clienteId'] ?? 0,
            $data['valorTotal'] ?? 0,
            $data['descricao'] ?? ""
        );
        
        try{
            self::service()->salvar($pedido);
        }
        catch (VazioValidacao $err) {
            return RenderView::render($response, ["erro" => $err->getMessage()], "Form");
        } 
        catch (FormatoValidacao $err) {
            return RenderView::render($response, ["erro" => $err->getMessage()], "Form");
        } 
        catch (UniqueConstraintViolationException $err) {
            return RenderView::render($response, ["erro" => "Registro duplicado"], "Form");
        }
        catch (Exception $e) {
            return RenderView::render($response, ["erro" => "Erro genérico: {$e->getMessage()}"], "Form");
        }

        return $response->withStatus(302)->withHeader('Location', '/pedidos');
    }

    public static function editar(Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $pedido = self::service()->buscarPorId($id);

        if(!isset($pedido))
            return $response->withStatus(302)->withHeader('Location', '/pedidos');
        
        return RenderView::render($response, ["pedido" => $pedido], "Form");
    }

    public static function atualizar(Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $pedido = self::service()->buscarPorId($id);

        if(!isset($pedido))
            return $response->withStatus(302)->withHeader('Location', '/pedidos');

        $data = $request->getParsedBody();

        // $pedido->clienteId = $data['clienteId'];
        $pedido->valorTotal = $data['valorTotal'];
        $pedido->descricao = $data['descricao'];

        try{
            self::service()->salvar($pedido);
        }
        catch (VazioValidacao $err) {
            return RenderView::render($response, ["erro" => $err->getMessage()], "Form");
        } 
        catch (FormatoValidacao $err) {
            return RenderView::render($response, ["erro" => $err->getMessage()], "Form");
        } 
        catch (UniqueConstraintViolationException $err) {
            return RenderView::render($response, ["erro" => "Registro duplicado"], "Form");
        }
        catch (Exception $e) {
            return RenderView::render($response, ["erro" => "Erro genérico: {$e->getMessage()}"], "Form");
        }
        
        return $response->withStatus(302)->withHeader('Location', '/pedidos');
    }

    public static function excluir(Request $request, Response $response) {
        $id = $request->getAttribute('id');
        self::service()->excluirPorId($id);
        
        return $response->withStatus(302)->withHeader('Location', '/pedidos');
    }
}