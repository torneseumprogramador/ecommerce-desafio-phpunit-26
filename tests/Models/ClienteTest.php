<?php
namespace Tests\Models;

use Danilo\EcommerceDesafio\Models\Cliente;
use PHPUnit\Framework\TestCase;

class ClienteTest extends TestCase
{
    public function testConstructor()
    {
        $cliente = new Cliente(1, "Danilo", "123456789", "danilo@example.com", "Rua ABC, 123");

        $this->assertSame(1, $cliente->id);
        $this->assertSame("Danilo", $cliente->nome);
        $this->assertSame("123456789", $cliente->telefone);
        $this->assertSame("danilo@example.com", $cliente->email);
        $this->assertSame("Rua ABC, 123", $cliente->endereco);
    }

    public function testNomeAttribute()
    {
        $cliente = new Cliente();
        $cliente->nome = "Danilo";

        $this->assertSame('Danilo', $cliente->nome);
    }

    public function testTelefoneAttribute()
    {
        $cliente = new Cliente();
        $cliente->telefone = "123456789";

        $this->assertSame("123456789", $cliente->telefone);
    }

    public function testEmailAttribute()
    {
        $cliente = new Cliente();
        $cliente->email = "danilo@example.com";

        $this->assertSame("danilo@example.com", $cliente->email);
    }

    public function testEnderecoAttribute()
    {
        $cliente = new Cliente();
        $cliente->endereco = "Rua ABC, 123";

        $this->assertSame("Rua ABC, 123", $cliente->endereco);
    }
}
