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

/**
 * @OA\Tag(name="Clientes")
 */

class ClientesController{
    private static $service;

    private static function service() {
        if(!isset($service)) $service = new ClienteServico(new ClienteRepositorioIlluminate());
        return $service;
    }

    /**
     * @OA\Get(
     *     path="/clientes",
     *     tags={"Clientes"},
     *     summary="Lista todos os clientes",
     *     security={{ "bearer_token": {} }},
     *     @OA\Parameter(
     *         name="pagina",
     *         in="query",
     *         description="Número da página para paginação",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="totalPagina",
     *         in="query",
     *         description="Quantidade total de itens por página",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Operação bem-sucedida"),
     *     @OA\Response(response=400, description="Erro na requisição"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
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
        $response->getBody()->write(json_encode($clientes->data));
        return $response->withStatus(200);
    }

    /**
     * @OA\Get(
     *     path="/clientes/{id}",
     *     tags={"Clientes"},
     *     summary="Mostra os detalhes de um cliente específico",
     *     security={{ "bearer_token": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cliente",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Operação bem-sucedida"),
     *     @OA\Response(response=404, description="Cliente não encontrado")
     * )
     */
    public static function mostrar(Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $cliente = self::service()->buscarPorId($id);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($cliente));
        return $response->withStatus(200);
    }

    /**
     * @OA\Post(
     *     path="/clientes",
     *     tags={"Clientes"},
     *     summary="Cria um novo cliente",
     *     security={{ "bearer_token": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados do cliente para criação",
     *         @OA\JsonContent(
     *             required={"nome", "telefone", "email", "endereco"},
     *             @OA\Property(property="nome", type="string", description="Nome do cliente"),
     *             @OA\Property(property="telefone", type="string", description="Telefone do cliente"),
     *             @OA\Property(property="email", type="string", format="email", description="E-mail do cliente"),
     *             @OA\Property(property="endereco", type="string", description="Endereço do cliente")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Cliente criado com sucesso"),
     *     @OA\Response(response=400, description="Erro na criação do cliente")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/clientes/{id}",
     *     tags={"Clientes"},
     *     summary="Atualiza um cliente específico",
     *     security={{ "bearer_token": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cliente a ser atualizado",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados do cliente para atualização",
     *         @OA\JsonContent(
     *             required={"nome", "telefone", "email", "endereco"},
     *             @OA\Property(property="nome", type="string", description="Nome do cliente"),
     *             @OA\Property(property="telefone", type="string", description="Telefone do cliente"),
     *             @OA\Property(property="email", type="string", format="email", description="E-mail do cliente"),
     *             @OA\Property(property="endereco", type="string", description="Endereço do cliente")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cliente atualizado com sucesso"),
     *     @OA\Response(response=400, description="Erro na atualização"),
     *     @OA\Response(response=404, description="Cliente não encontrado")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/clientes/{id}",
     *     tags={"Clientes"},
     *     summary="Exclui um cliente específico",
     *     security={{ "bearer_token": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cliente a ser excluído",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Cliente excluído com sucesso"),
     *     @OA\Response(response=404, description="Cliente não encontrado")
     * )
     */
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