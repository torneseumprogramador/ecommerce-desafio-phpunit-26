#!/bin/bash

# Verifica se a porta 8081 está sendo utilizada
if lsof -i:8081 | grep LISTEN; then
    echo "Aplicação já está rodando na porta 8081."
else
    echo "Aplicação não está rodando na porta 8081."
    echo "Iniciando a aplicação..."
    # Navega até a pasta do script e o executa
    cd /var/www/html/ecommerce-desafio-phpunit-26
    nohup sh start.sh &
fi
