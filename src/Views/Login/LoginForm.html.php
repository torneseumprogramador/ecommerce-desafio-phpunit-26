<header class="py-5">
    <div class="container px-5 pb-5">
        <div class="row gx-5 align-items-center">
            <div class="col-xxl-12">
                <h3 class="card-title text-center">Login</h3>
                <form method="post" action="/login">
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Digite o seu email">
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control" id="password" name="senha" required placeholder="Digite a sua senha">
                    </div>

                    <?php if ( isset($erro) ) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $erro ?>
                        </div>
                    <?php } ?>
                    <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</header>