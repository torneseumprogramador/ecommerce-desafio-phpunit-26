<header class="py-5">
    <div class="container px-5 pb-5">
        <div class="row gx-5 align-items-center">
            <div class="col-xxl-12">
                <h1>Lista de pedidos</h1>
                <a href="/pedidos/novo" class="btn btn-primary">Novo</a>
                <hr>

                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Valor Total</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Data</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pedidos as $pedido) { ?>
                        <tr>
                            <th scope="row"><?= $pedido->id ?></th>
                            <td><?= $pedido->cliente->nome ?></td>
                            <td>R$ <?= number_format($pedido->valorTotal, 2, ',', '.') ?></td>
                            <td><?= $pedido->descricao ?></td>
                            <td><?= $pedido->data->format('d/m/Y H:i') ?></td>
                            <td style="width:10px">
                                <a href="/pedidos/<?= $pedido->id ?>/editar" class="btn btn-warning">Alterar</a>
                            </td>
                            <td style="width:10px">
                                <a href="/pedidos/<?= $pedido->id ?>/excluir" onclick="return confirm('Confirma?')" class="btn btn-danger">Excluir</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</header>