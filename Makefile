#!/usr/bin/make -f
.SILENT:
.PHONY: build up down ssh sql logs reset all

## Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

## Exibe as instruções de uso.
help:
	printf "${COLOR_COMMENT}Uso:${COLOR_RESET}\n"
	printf " make [comando]\n\n"
	printf "${COLOR_COMMENT}Comandos disponíveis:${COLOR_RESET}\n"
	awk '/^[a-zA-Z\-\_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

## Constroi a imagem.
build:
	@echo 🐳🐘 Construindo as imagens.
	docker compose build

## Instala as dependencias do php.
composer_install:
	@echo 📦 Instalando as dependências do Composer.
	docker compose run --no-deps php bash -c "composer install"

## Inicializa o projeto.
up:
	@echo 🚀 Subindo o ambiente
	sudo chmod 777 -R app/var/ || true
	docker network create app || true

ifeq (build, $(filter build,$(MAKECMDGOALS)))
	docker compose up -d --build
else
	docker compose up -d
endif
	make composer_install

## Reinicia a aplicação.
reset:
	@echo 💾 Criando e populando banco de dados local.
	docker compose run php bin/console doctrine:migrations:migrate --no-interaction

## Conecta-se ao container php.
ssh:
	docker compose exec php bash

bash:
	docker compose exec php bash

## Remove containers da aplicação.
down:
	@echo 🔴 Removendo os serviços.
	docker compose down

## Executa os testes da aplicação.
test:
	@echo ► Executando testes
	docker compose exec php composer test

## Para containers da aplicação.
stop:
	@echo 🔴 Parando os serviços.
	docker compose stop

## Conecta-se ao cliente SQL do container mysql.
sql:
	docker compose exec mysql mysql -uroot -proot

## Exibe os logs da aplicação.
logs: 
	docker compose logs -f -t

## Apaga arquivos gerados dinâmicamente pelo projeto (containers docker, vendor, etc)
clean:
	@echo 🗑️ Removendo arquivos gerados automaticamente pelo projeto.
	sudo rm -rf app/vendor
	sudo rm -rf app/data
	sudo rm -rf app/var/cache
	sudo rm -rf app/var/log
	docker compose down --rmi local --remove-orphans --volumes

## Libera espaço em disco (apaga dados do docker em desuso)
freespace:
	@echo 🗑️ Apagando arquivos do Docker que não estão sendo utilizados
	docker system prune --all --volumes --force

## Executa o lint.
lint:
	docker compose exec php composer lint

## Executa a correção automática de lint.
lint-fix:
	docker compose exec php composer lint-fix

## Executa o coverage
coverage:
	docker compose exec php composer coverage