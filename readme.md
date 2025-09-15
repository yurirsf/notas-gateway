# Skeleton PHP em Symfony

Esqueleto para começar um novo micro serviço PHP com a seguinte stack:

- PHP 8.2
- Symfony Framework 7.3.X
- Doctrine & Doctrine Migrations
- MySQL
- Ferramentas de QA
- Open API

## Instalação

1. Instale a última versão do `docker` e `docker-compose`.

2. Clone este repositório.

3. Suba o ambiente:

`make up`

4. Para ver se está tudo ok, acesse [http://127.0.0.1:8080/healthcheck](http://127.0.0.1:8080/healthcheck) e veja uma resposta como:

``` 
{
    "msg":"ok",
    "datetime":"2020-06-21T01:49:25Z",
    "timestamp":"1592704165"
}
``` 

Execute `make help` para conhecer outros comandos.

## CLI

`$ make bash`

Digite o comando abaixo para listar todos os possiveis comandos
`$ console`

Gerar controller:
`$ console make:controller`

Gerar entidade:
`$ console make:entity`

Exibir as rotas:
`$ console debug:router`

## Depuração

- [Configurar depurador (Xdebug)](./docs/debugger.md)
- [Symfony Profiler](http://127.0.0.1:8080/_profiler) [(docs)](https://symfony.com/doc/current/profiler.html)
- Comandos `console debug:alguma-coisa`

## Padrão de codificação

1. Regras especificadas em `phpcs.xml`, `phpstan.neon`, `psalm.xml`.
1. Proibido o uso de `else`.
2. Aplicar Object Calisthenics - [vídeo](https://www.youtube.com/watch?v=u-w4eULRrr0) [slides](https://pt.slideshare.net/guilhermeblanco/php-para-adultos-clean-code-e-object-calisthenics)
    - Early returns
    - Apenas 1 nível de identação
3. Um objeto jamais pode se permitir entrar em um estádo inválido. Nunca construir um objeto inválido.
4. SOLID

## Migration

1. Para gerar os scripts de migration após alterações nos schemas (entities), execute o seguinte comando:

`docker-compose exec php console doctrine:migrations:diff`

2. Os scripts serão gerados no diretório `api/src/Core/Migrations`

3. Os scripts de migration são executados no comando `make up`. Para executá-los manualmente em ambiente local, execute o seguinte comando:

`docker-compose exec php console doctrine:migrations:migrate`

## Após criar o fork

### Configurar servidor de integração continua

Acessar o [CI (Drone)](https://github-drone.superlogica.com/) e localizar o repositório. Clicar no botão `Activate`.

Peça para o time DevOps:

1. Definir o segredo `SL_SONAR_PROJECT_TOKEN` no repositório - necessário para que os reports sejam enviados para o [SonarQube](https://sonar.superlogica.com).
2. Marcar o repositório como `trusted` no Drone - mecessário para que seja possível montar volumes no host. 

Ativar o projeto no servidor de integração continua da empresa: 

## Infraestrutura como código:
    - [Repositório do Skeleton](https://github.com/Superlogica/skeleton-php-infra)
    - [API Gateway](https://github.com/Superlogica/skeleton-php-api-gateway-http)
