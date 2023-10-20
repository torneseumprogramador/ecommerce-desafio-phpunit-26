<?php
namespace Tests\Mock;
use Danilo\EcommerceDesafio\Repositorios\Interfaces\IRepositorio;

class MemoryRepositorio implements IRepositorio{
    private static $lista = [];

    public function salvar($obj)
    {
        if (isset($obj->id) && $obj->id != "" && $obj->id != 0) {
            // Procura pelo cliente existente com o ID fornecido
            foreach (MemoryRepositorio::$lista as $index => $clienteExistente) {
                if ($clienteExistente->id == $obj->id) {
                    // Atualiza o cliente existente
                    MemoryRepositorio::$lista[$index] = $obj;
                    return;
                }
            }
        } else {
            // Define um novo ID se não estiver definido
            $obj->id = time();
        }

        // Adiciona o novo cliente à lista
        MemoryRepositorio::$lista[] = $obj;
    }

    public function buscar($params=[], $pagina=1, $totalPagina=5) : array {
        return MemoryRepositorio::$lista;
    }

    public function buscarPorId($id) {
        foreach( MemoryRepositorio::$lista as $item ){
            if($item->id == $id) return $item;
        }
        return null;
    }

    public function excluirPorId($id)
    {
        MemoryRepositorio::$lista = array_filter(MemoryRepositorio::$lista, function($item) use ($id) {
            return $item->id !== $id;
        });
    }
}