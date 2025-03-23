PROJECT_NAME="$(shell basename "$(PWD)")"
PROJECT_DIR="$(shell pwd)"
DOCKER_COMPOSE="$(shell which docker-compose)"
DOCKER="$(shell which docker)"
CONTAINER_PHP=php-unit
CONTAINER_PHP_NAME=$(APP_NAME)-${CONTAINER_PHP}


# Загрузка переменных из .env.local файла
ifneq ("$(wildcard .env.local)","")
	include .env.local
	export
endif



init: generate-env  generate-override up sleep-5 ci m-migrate generate-env-test right

test: test-up test-down

restart: down up


.PHONY: init restart sleep-5

##
## Docker
## ----------------------

sleep-5:
	sleep 5

up:
	docker-compose  --env-file .env.local up --build -d

down:
	docker-compose  --env-file .env.local down --remove-orphans


.PHONY:  sleep-5 up down

##
## Generate file
## ----------------------

generate-env:
	@if [ ! -f .env.local ]; then \
		cp .env .env.local && \
		sed -i "s/^POSTGRES_PASSWORD=/POSTGRES_PASSWORD=$(shell openssl rand -hex 8)/" .env.local; \
		sed -i "s/^APP_SECRET=/APP_SECRET=$(shell openssl rand -hex 8)/" .env.local; \
	fi

generate-env-test:
	@if [ ! -f .env.test.local ]; then \
	cp .env.test .env.test.local && \
		sed -i "s/^POSTGRES_PASSWORD=/POSTGRES_PASSWORD=$(shell openssl rand -hex 8)/" .env.test.local; \
	fi

generate-override:
	@if [ ! -f docker-compose.override.yaml ]; then \
		cp docker-compose.override.yaml.example docker-compose.override.yaml; \
	fi

.PHONY: generate-env generate-env-test generate-override

##
## Bash, composer
## ----------------------

bash:
	${DOCKER_COMPOSE} exec -it ${CONTAINER_PHP} /bin/bash

ps:
	${DOCKER_COMPOSE} ps

logs:
	${DOCKER_COMPOSE} logs -f

ci:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} composer install --no-interaction

cu:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} composer update -w

c-dump:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} composer dump-autoload

.PHONY: bash ps logs ci cu c-dump


##
## Database
## ----------------------

m-create:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} bin/console doctrine:database:create --if-not-exists -n

m-create-test:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} bin/console --env=test doctrine:database:create --if-not-exists -n

m-list:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} bin/console doctrine:migrations:list

m-diff:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} bin/console doctrine:migrations:diff

m-up:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} bin/console doctrine:migrations:migrate

m-migrate:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} bin/console doctrine:migrations:migrate -n

m-prev:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} bin/console doctrine:migrations:migrate prev


##
## Other
## ----------------------

cc:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} bin/console cache:clear

right:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} chown -R www-data:www-data .

ip:
	${DOCKER} inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' ${CONTAINER_PHP_NAME}

.PHONY: m-create m-list m-diff m-up m-migrate m-prev cc right ip

stan:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} vendor/bin/phpstan analyse src

#cs-fix:
#	 ${DOCKER_COMPOSE} exec ${CONTAINER_PHP}  PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix src

test-up:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} php bin/phpunit

test-down:
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} bin/console  --env=test doctrine:database:drop --force


.PHONY: stan cs-fix test-start test-finish