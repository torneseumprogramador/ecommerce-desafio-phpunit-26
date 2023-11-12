# Imagem base PHP com Apache
FROM php:8.1-apache

# Atualizar pacotes e instalar extensões necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Instalar extensões do PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Ativar mod_rewrite para Apache
RUN a2enmod rewrite

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos de dependências do Composer
COPY composer.json composer.lock ./

# Instalar dependências com o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Copiar o código fonte da aplicação
COPY . .

# Dump do autoloader do Composer para otimizar o carregamento da classe
RUN composer install
RUN composer dump-autoload --optimize

# Expôr a porta 8081
EXPOSE 8081

# Usar o script de inicialização do Composer
CMD ["composer", "start"]

