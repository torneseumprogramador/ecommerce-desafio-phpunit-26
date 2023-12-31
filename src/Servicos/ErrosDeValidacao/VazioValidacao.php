<?php
namespace Danilo\EcommerceDesafio\Servicos\ErrosDeValidacao;

class VazioValidacao extends \Exception
{
    public function __construct($message = "Não pode ser vazio", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
