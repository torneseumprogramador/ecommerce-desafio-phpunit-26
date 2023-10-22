<?php 
namespace Danilo\EcommerceDesafio\Models;

class Pedido{
    public function __construct($_id=0, $_clienteId=0, $_valorTotal=0, $_descricao="", $_data=null) {
        $this->id = $_id;
        $this->clienteId = $_clienteId;
        $this->valorTotal = $_valorTotal;
        $this->descricao = $_descricao;
        $this->data = $_data == null ? new \DateTime() : $_data;
    }

    public $id;
    public $clienteId;
    public $cliente;
    public $valorTotal;
    public $descricao;
    public $data;
}