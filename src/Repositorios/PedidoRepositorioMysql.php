<?php 
namespace Danilo\EcommerceDesafio\Repositorios;

use Danilo\EcommerceDesafio\Repositorios\Infraestrutura\MysqlRepositorio;
use Danilo\EcommerceDesafio\Repositorios\Interfaces\IRepositorio;
use Danilo\EcommerceDesafio\Models\Pedido;
use Danilo\EcommerceDesafio\Models\Cliente;
use DateTime;

class PedidoRepositorioMysql implements IRepositorio {

    public function __construct() {
        $this->repo = MysqlRepositorio::instancia();
    }

    private $repo;

    public function salvar($obj) {
        $params = [
            "cliente_id"   => $obj->clienteId,
            "valor_total"  => $obj->valorTotal,
            "descricao"    => $obj->descricao,
            "data"         => $obj->data->format('Y-m-d H:i:s'),
        ];

        if (isset($obj->id) && $obj->id > 0) {
            $params["id"] = $obj->id;
            $sql = "UPDATE pedidos SET cliente_id = :cliente_id, valor_total = :valor_total, descricao = :descricao, data = :data WHERE id = :id";
        } else {
            $sql = "INSERT INTO pedidos (cliente_id, valor_total, descricao, data) VALUES (:cliente_id, :valor_total, :descricao, :data)";
        }

        $this->repo->execute($sql, $params);
    }

    public function buscar($params=[], $pagina=1, $totalPagina=5) : array {
        $pagina = max(1, isset($pagina) ? intval($pagina) : 1);
        $offset = ($pagina - 1) * $totalPagina;
    
        $where = " WHERE 1 = 1 ";
    
        foreach ($params as $key => $value) {
            if ($key == "descricao") {
                $where .= " AND pedidos.descricao LIKE :descricao ";
                $params["descricao"] = '%' . $params["descricao"] . '%';
            } else {
                $where .= " AND pedidos.$key = :$key ";
            }
        }
    
        $query = "SELECT pedidos.*, clientes.nome as cliente_nome, clientes.telefone as cliente_telefone, clientes.email as cliente_email, clientes.endereco as cliente_endereco FROM pedidos";
        $query .= " INNER JOIN clientes ON pedidos.cliente_id = clientes.id";
        $query .= $where . " ORDER BY pedidos.id DESC LIMIT $totalPagina OFFSET $offset";
    
        $dados = $this->repo->buscar($query, $params);
    
        $pedidos = [];
        foreach($dados as $dado){
            $pedidos[] = $this->buildPedido($dado);
        }
    
        return $pedidos;
    }
    

    public function buscarPorId($id): Pedido {
        $dados = $this->buscar(["id" => $id]);

        if(count($dados) > 0){
            return $dados[0];
        }

        return null;
    }

    public function excluirPorId($id) {
        $sql = "DELETE FROM pedidos WHERE id = :id";
        $this->repo->execute($sql, ['id' => $id]);
    }

    private function buildPedido($dado){
        if (!$dado) return null;

        $cliente = new Cliente(
            $dado["cliente_id"],
            $dado["cliente_nome"],
            $dado["cliente_telefone"],
            $dado["cliente_email"],
            $dado["cliente_endereco"]
        );

        $pedido = new Pedido(
            $dado["id"],
            $dado["cliente_id"],
            $dado["valor_total"],
            $dado["descricao"],
            new \DateTime($dado["data"]) // ficar em alerta com timezone
        );

        $pedido->cliente = $cliente;

        return $pedido;
    }
}
