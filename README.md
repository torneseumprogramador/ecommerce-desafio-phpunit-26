## Um dos maiores problemas até agora doi configurar o selenium com um driver compativel no final ficamos com o firefox

### faça o download dos itens abaixo
- https://selenium-release.storage.googleapis.com/index.html?path=3.5/
- https://github.com/mozilla/geckodriver/releases

### Certifique-se que vc tem em seu diretório os itens abaixo:
```shell
$ ls
geckodriver # Firefox Webdriver
selenium-server-standalone-3.5.3.jar # Selenium, server stanalone
```

### Iniciar o servidor selenium
```shell
java -Dwebdriver.firefox.driver=/Users/danilo/Downloads/geckodriver -jar selenium-server-standalone-3.5.3.jar
```

### Rodar a aplicação
```shell
sh start.sh
```

### Rodar o teste de comportamento
```shell
sh test-comportamento.sh
```