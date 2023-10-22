<?php 
namespace Danilo\EcommerceDesafio\Servicos;

use Danilo\EcommerceDesafio\Models\Pedido;
use Danilo\EcommerceDesafio\Servicos\ErrosDeValidacao\VazioValidacao;
use Danilo\EcommerceDesafio\Servicos\ErrosDeValidacao\FormatoValidacao;

class PedidoServico {
    public function __construct($driver) {
        $this->driver = $driver;
    }

    private $driver;

    public function salvar(Pedido $pedido){
        if(!isset($pedido->clienteId) || $pedido->clienteId == 0)
            throw new VazioValidacao("Cliente é obrigatório para um pedido");

        if(!isset($pedido->valorTotal) || $pedido->valorTotal == "")
            throw new VazioValidacao("Valor do pedido precisa estar preenchido");
        
        if (is_string($pedido->valorTotal)) {
            $pedido->valorTotal = preg_replace('/[^0-9,.]/', '', $pedido->valorTotal);

            if (strpos($pedido->valorTotal, '.') !== false && strpos($pedido->valorTotal, ',') !== false) {
                $pedido->valorTotal = str_replace('.', '', $pedido->valorTotal);
                $pedido->valorTotal = str_replace(',', '.', $pedido->valorTotal);
            }
            else if ( strpos($pedido->valorTotal, ',') !== false ){
                $pedido->valorTotal = str_replace(',', '.', $pedido->valorTotal);
            }

            $pedido->valorTotal = floatval($pedido->valorTotal);
        }

        if($pedido->valorTotal < 1)
            throw new VazioValidacao("Valor do pedido precisa ser maior que zero");

        $this->driver->salvar($pedido);
    }

    public function buscar($params=[], $pagina=1, $totalPagina=5){
        return $this->driver->buscar($params, $pagina, $totalPagina);
    }

    public function buscarPorId($id){
        return $this->driver->buscarPorId($id);
    }

    public function excluirPorId($id){
        return $this->driver->excluirPorId($id);
    }
}