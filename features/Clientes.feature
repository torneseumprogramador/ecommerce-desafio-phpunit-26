# language: pt
Funcionalidade: CRUD de clientes

Cenário: Cadastrar um cliente
  Dado que eu estou na pagina de novo cliente
  Quando eu preencho todos os campos e clico em cadastrar
  Então eu devo ver o item na tabela de clientes

Cenário: Cadastrar um cliente com telefone errado
  Dado que eu estou na pagina de novo cliente
  Quando eu preencho o telefone errado depois clico em cadastrar
  Então eu devo ver a mensagem de erro

Cenário: Cadastrar dois clientes iguais
  Dado que eu estou na pagina de novo cliente
  Quando eu cadastro 2 vezes o cliente duplicado
  Então devo te a mensagem de erro duplicado

