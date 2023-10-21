<?php

namespace Steps;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;
use Danilo\EcommerceDesafio\Repositorios\Infraestrutura\MysqlRepositorio;

class ClientesSteps extends RawMinkContext implements Context, SnippetAcceptingContext
{
    /**
     * @Given que eu estou na pagina de novo cliente
     */
    public function queEuEstouNaPaginaDeNovoCliente()
    {
        MysqlRepositorio::instancia()->execute("truncate table clientes");
        $this->getSession()->visit('http://localhost:8080/clientes/novo');
    }

    /**
     * @When eu preencho todos os campos e clico em cadastrar
     */
    public function euPreenchoTodosOsCamposEClicoEmCadastrar()
    {
        $page = $this->getSession()->getPage();
        $page->fillField('nome', "Nome Teste");
        $page->fillField('email', 'teste@email.com');
        $page->fillField('telefone', '(31) 4444-4444');
        $page->fillField('endereco', 'Rua Teste, 123');

        $page->pressButton('Enviar');
    }

    /**
     * @Then eu devo ver o item na tabela de clientes
     */
    public function euDevoVerOItemNaTabelaDeClientes()
    {
        $page = $this->getSession()->getPage();

        // Verifica se a tabela contém as informações do cliente que adicionamos
        Assert::assertTrue($page->hasContent('Nome Teste'));
        Assert::assertTrue($page->hasContent('teste@email.com'));
        Assert::assertTrue($page->hasContent('(31) 4444-4444'));
        Assert::assertTrue($page->hasContent('Rua Teste, 123'));
    }

     /**
     * @When eu preencho o telefone errado depois clico em cadastrar
     */
    public function euPreenchoOTelefoneErradoDepoisClicoEmCadastrar()
    {
        $page = $this->getSession()->getPage();

        // Verifica se a tabela contém as informações do cliente que adicionamos
        $page->fillField('nome', "Nome Teste");
        $page->fillField('email', 'testetefeleoneerrado@email.com');
        $page->fillField('telefone', '(31) xxxx4444-4444');
        $page->fillField('endereco', 'Rua Teste, 123');

        $page->pressButton('Enviar');
    }

    /**
     * @Then eu devo ver a mensagem de erro
     */
    public function euDevoVerAMensagemDeErro()
    {
        $page = $this->getSession()->getPage();
        Assert::assertTrue($page->hasContent('O formato do telefone precisa ser (00) 00000-0000 ou (00) 0000-0000'));
    }

     /**
     * @When eu cadastro :arg1 vezes o cliente duplicado
     */
    public function euCadastroVezesOClienteDuplicado($arg1)
    {
        $sql = "INSERT INTO clientes (nome, email, telefone, endereco) VALUES ('Duplicado', 'duplicado@email.com', '(31) 4444-4444', 'Rua Teste, 123 duplicado')";
        MysqlRepositorio::instancia()->execute($sql);

        $page = $this->getSession()->getPage();

        $page->fillField('nome', "Duplicado");
        $page->fillField('email', 'duplicado@email.com');
        $page->fillField('telefone', '(31) 4444-4444');
        $page->fillField('endereco', 'Rua Teste, 123 duplicado');

        $page->pressButton('Enviar');
    }

    /**
     * @Then devo te a mensagem de erro duplicado
     */
    public function devoTeAMensagemDeErroDuplicado()
    {
        $page = $this->getSession()->getPage();
        Assert::assertTrue($page->hasContent('Registro duplicado'));
    }
}
