
# gera a doc do swagger
./vendor/bin/openapi --output src/Swagger/swagger.json src/Controllers/

kill -9 $(lsof -t -i:8081)

sh start.sh &

echo "Aguardando start da api ..."
sleep 3

# mac
open http://localhost:8081/swagger/index.html

# explorer
# explorer http://localhost:8081/swagger/index.html