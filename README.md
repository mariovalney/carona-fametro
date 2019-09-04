# Carona Fametro

Welcome to the Source.

https://www.useloom.com/share/c1f97813541b4194bf1f68cac181975e

## Setup de Desenvolvimento

Usamos o Docker e o Docker-Compose.

### Ambiente de Produção

```
Domain: caronafametro.mariovalney.com
PHP Version: 7.1
MySQL Version: 5.7.18
```

### Configuração

Adicione um arquivo `config.php` ao lado do `docker-compose.yml` com as configurações do Avant para o seu ambiente local. Use `config.sample.php` como base.

### Como usar o Docker

Use o comando `docker-compose up`.

### Desenvolvimento

Rode `npm install` e `composer install`.

## Problemas?

Se tiver problemas com permissão de pasta, não esqueça de adicionar o seu usuário ao grupo `www-data` e definir o dono do repositório como `usuario:www-data`.

Após isso, se não conseguir atualizar nada ou baixar temas/plugins, tente corrigir as permissões (esteja na raiz do WordPress):

```bash
sudo find . -type d -exec chmod 775 {} \; && sudo find . -type f -exec chmod 664 {} \;
```