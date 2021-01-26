DC=docker-compose
DCEXEC=${DC} exec
DCEXEC_PHP=${DCEXEC} -u www-data php

.PHONY: up down install bash vendor migrate fixtures assets db-drop db-create db-refresh

up:
	${DC} up -d --build

down:
	${DC} down

bash:
	${DCEXEC_PHP} bash

vendor:
	${DCEXEC_PHP} composer install

migrate:
	$(DCEXEC_PHP) php bin/console doctrine:migrations:migrate --no-interaction

fixtures:
	$(DCEXEC_PHP) php bin/console doctrine:fixtures:load --no-interaction

assets:
	$(DCEXEC_PHP) bin/console assets:install

db-drop:
	$(DCEXEC_PHP) bin/console d:d:d --force

db-create:
	$(DCEXEC_PHP) bin/console d:d:c

db-refresh: db-drop db-create migrate

build: up vendor migrate fixtures