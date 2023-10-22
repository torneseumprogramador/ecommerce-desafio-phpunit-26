<?php 
namespace Danilo\EcommerceDesafio\Repositorios;

use Danilo\EcommerceDesafio\Repositorios\Infraestrutura\IlluminateRepositorio;
use Danilo\EcommerceDesafio\Repositorios\Interfaces\IRepositorio;
use Danilo\EcommerceDesafio\Models\Pedido;
use Danilo\EcommerceDesafio\Models\Cliente;

class PedidoRepositorioIlluminate implements IRepositorio {
    public function __construct() {
        $this->repo = IlluminateRepositorio::instancia();
    }

    private $repo;

    public function salvar($obj){
        if (isset($obj->id) && $obj->id > 0) {
            return $this->repo->table('pedidos')
                ->where('id', $obj->id)
                ->update([
                    'cliente_id'    => $obj->clienteId,
                    'valor_total'   => $obj->valorTotal,
                    'descricao'     => $obj->descricao,
                    'data'          => $obj->data
                ]);
        }
        
        // Caso contrário, inserimos um novo registro e retornamos o ID inserido
        return $this->repo->table('pedidos')->insertGetId([
            'cliente_id'    => $obj->clienteId,
            'valor_total'   => $obj->valorTotal,
            'descricao'     => $obj->descricao,
            'data'          => $obj->data
        ]);
    }

    public function buscar($params=[], $pagina=1, $totalPagina=5) : array {
        // Iniciamos a consulta na tabela 'pedidos'
        $query = $this->repo->table('pedidos')
            ->select('pedidos.*', 'clientes.nome as cliente_nome', 'clientes.telefone as cliente_telefone', 'clientes.email as cliente_email', 'clientes.endereco as cliente_endereco')
            ->join('clientes', 'pedidos.cliente_id', '=', 'clientes.id');
    
        // Aplicamos os filtros baseados nos parâmetros fornecidos
        foreach ($params as $key => $value) {
            $query = $query->where($key, $value);
        }
    
        // calculo da paginação
        $pagina = max(1, isset($pagina) ? intval($pagina) : 1);
        $offset = ($pagina - 1) * $totalPagina;
    
        // Aplicamos a limitação e o offset para a paginação
        $query = $query->limit($totalPagina)->offset($offset);

        $query = $query->orderBy('id', 'desc');
    
        // Executamos a consulta e retornamos os resultados

        $dados = $query->get();

        $pedidos = [];
        foreach($dados as $dado){
            $pedidos[] = $this->buildPedido($dado);
        }

        return $pedidos;
    }

    public function buscarPorId($id) : Pedido {
        $dado = $this->repo->table('pedidos')
            ->select('pedidos.*', 'clientes.nome as cliente_nome', 'clientes.telefone as cliente_telefone', 'clientes.email as cliente_email', 'clientes.endereco as cliente_endereco')
            ->join('clientes', 'pedidos.cliente_id', '=', 'clientes.id')
            ->where('pedidos.id', $id)
            ->first();

        return $this->buildPedido($dado);
    }

    public function excluirPorId($id){
        return $this->repo->table('pedidos')->where('id', $id)->delete();
    }

    private function buildPedido($dado){
        if (!$dado) return null;

        $cliente = new Cliente(
            $dado->cliente_id,
            $dado->cliente_nome,
            $dado->cliente_telefone,
            $dado->cliente_email,
            $dado->cliente_endereco
        );

        $pedido = new Pedido(
            $dado->id,
            $dado->cliente_id,
            $dado->valor_total,
            $dado->descricao,
            new \DateTime($dado->data)
        );

        $pedido->cliente = $cliente;

        return $pedido;
    }
}