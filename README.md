# Carona Fametro

Welcome to the Source.

## Setup de Desenvolvimento

Versionamos apenas a parte que vai para deploy, por tanto o setup de desenvolvimento pode variar para cada desenvolvedor, bastando respeitar os requisítos mínimos do framework AVANT e as configurações do servidor de Produção, para consistência no fluxo de deploy.

No entando, sugerimos o uso do docker.

### Ambiente de Produção

```
Domain: caronafametro.mariovalney.com
PHP Version: 7.1
MySQL Version: 5.7.18
```

### Docker Compose

```yml
web:
  build: .docker
  links:
    - mysql:mysql
  ports:
    - "80:8080"
  volumes:
    - ./www:/var/www/html
    - ./config.php:/var/www/html/config.php

mysql:
  image: mysql:5.7
  environment:
    - MYSQL_ROOT_PASSWORD=root
  volumes:
    - "./.data/db:/var/lib/mysql"
```

### Dockerfile

```
FROM php:7.1-apache

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite
RUN service apache2 restart
```

### Config File

Adicione um arquivo `config.php` ao lado do `docker-compose.yml` com as configurações do Avant para o seu ambiente local. Use `config.sample.php` como base.

### Como usar o Docker

Após instalar o Docker e o Docker-compose, crie um diretório para o projeto. Dentro dele crie os diretórios `www` e `.docker`.

Clone o repositório dentro do primeiro e insira o `Dockerfile` no segundo. Após isso o `docker-compose.yml` deve ir na raiz do projeto (ao lado dos dois diretórios criados).

Crie também o arquivo de configuração `config.php` e adicione ao lado do `docker-compose.yml` (esse arquivo é usado apenas localmente - com o Docker).

Agora basta rodar o comando `docker-compose up` que a imagem será criada. Crie um host para o site.

### Desenvolvimento

Rode `npm install` e `composer install`.

### Problemas?

Se tiver problemas com permissão de pasta, não esqueça de adicionar o seu usuário ao grupo `www-data` e definir o dono do repositório como `usuario:www-data`.

Após isso, se não conseguir atualizar nada ou baixar temas/plugins, tente corrigir as permissões (esteja na raiz do WordPress):

```bash
sudo find . -type d -exec chmod 775 {} \; && sudo find . -type f -exec chmod 664 {} \;
```

## Deploy

Para fazer o deploy basta um `push` ou merge para o branch `production`.