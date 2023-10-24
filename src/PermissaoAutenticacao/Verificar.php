<?php
namespace Danilo\EcommerceDesafio\PermissaoAutenticacao;

class Verificar{
    public static function permissionamento($request, $permissoes): bool {
        $controller = $request->getAttribute('controller');
        $action = $request->getAttribute('action');
        foreach ($permissoes as $permissao) {
            if (strtolower($permissao->controller) === strtolower($controller) && strtolower($permissao->action) === strtolower($action)) {
                return true;
            }
        }
    
        return false;
    }
}
