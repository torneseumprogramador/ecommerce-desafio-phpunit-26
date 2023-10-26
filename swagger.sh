kill -9 $(lsof -t -i:8081)

sh start.sh &

echo "Aguardando start da api ..."
sleep 3

./vendor/bin/openapi --output src/Swagger/openapi.json src/Controllers/

# mac
open swagger/index.html

# explorer
# explorer swagger/index.html