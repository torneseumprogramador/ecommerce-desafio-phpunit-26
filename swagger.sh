#!/usr/bin/env bash

SWAGGER_FILE="src/Swagger/swagger.json"

# gera a doc do swagger
./vendor/bin/openapi --output $SWAGGER_FILE src/Controllers/

# JSON a ser incluído
JSON_TO_ADD='
{
  "openapi": "3.0.0",
  "info": {
    "title": "API Desafio",
    "version": "0.1",
    "description": "API para E-commerce Desafio",
    "contact": {
        "name": "Danilo Aparecido",
        "url": "https://www.torneseumprogramador.com.br/cursos/desafio_php",
        "email": "suporte@torneseumprogramador.com.br"
    },
    "license": {
        "name": "Licença Proprietária"
    }
  },
  "components": {
    "securitySchemes": {
      "bearer_token": {
        "type": "http",
        "description": "Use a JWT Bearer token para autenticar",
        "name": "Bearer",
        "in": "header",
        "scheme": "bearer",
        "bearerFormat": "JWT"
      }
    }
  }
}
'

echo "$JSON_TO_ADD" > tmp_add.json
jq -s '.[0] * .[1]' "$SWAGGER_FILE" tmp_add.json > tmp.json && mv tmp.json "$SWAGGER_FILE"
rm tmp_add.json

kill -9 $(lsof -t -i:8081)

sh start.sh &

echo "Aguardando start da api ..."
sleep 3

# mac
open http://localhost:8081/swagger/index.html

# explorer
# explorer http://localhost:8081/swagger/index.html