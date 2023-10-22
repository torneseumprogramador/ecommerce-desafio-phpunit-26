<header class="py-5">
    <div class="container px-5 pb-5">
        <div class="row gx-5 align-items-center">
            <div class="col-xxl-12">
                <h1><?= isset($pedido) ? "Edição do ID $pedido->id" : "Novo Pedido" ?></h1>
                <hr>

                <?php if( isset($erro) && $erro != "" ) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $erro ?>
                    </div>
                <?php } ?>

                <form action="<?= isset($pedido) ? "/pedidos/$pedido->id" : "/pedidos" ?>" method="post" >
                    <div class="form-group">
                        <label for="clienteId">Cliente:</label>

                        <?php if( !isset($pedido) ) { ?>
                            <input type="text" onkeyup="preecheSelect(this)" class="form-control" placeholder="Digite o nome do cliente para preencher a seleção abaixo" style="border-radius: 5px 5px 0px 0px;border-bottom: 0px;">
                            <select class="form-control" id="clienteId" name="clienteId" style="border-radius: 0px 0px 5px 5px;border-top: 0px;">
                                <option value="0">
                                    [Selecione]
                                </option>
                            </select>
                        <?php } else { ?>
                            <select class="form-control" id="clienteId" name="clienteId" disabled="disabled">
                                <option value="<?= $pedido->cliente->id ?>" selected >
                                    <?= $pedido->cliente->nome ?>
                                </option>
                            </select>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="valorTotal">Valor Total</label>
                        <input type="text" class="form-control" id="valorTotal" name="valorTotal" value="<?= isset($pedido) ? $pedido->valorTotal : "" ?>" placeholder="Digite o valor total do pedido">
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" placeholder="Detalhe aqui os itens que você pegou"><?= isset($pedido) ? $pedido->descricao : "" ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <a href="/pedidos" class="btn btn-default">Lista de pedidos</a>
                </form>
            </div>
        </div>
    </div>
</header>