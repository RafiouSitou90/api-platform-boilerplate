DC=docker-compose
DE=docker exec
COMPOSER=$(DC) exec COMPOSER_MEMORY_LIMIT=-1 composer
SYMFONY_CONSOLE=$(DC) exec php bin/console

up:
	$(DC) up -d

stop:
	$(DC) stop

down:
	$(DC) down

rebuild:
	$(DC) down -v --remove-orphans
	$(DC) rm -vsf
	$(DC) up -d --build

container-php:
	$(DE) -it container_php bash

## —— cache ———————————————————————————————————————————————————————————————
cache: ## Reset the database
	$(SYMFONY_CONSOLE) cache:clear
	$(SYMFONY_CONSOLE) cache:warmup

## —— database ———————————————————————————————————————————————————————————————
clean-db: ## Reset the database
	$(SYMFONY_CONSOLE) doctrine:database:drop --if-exists --force
	$(SYMFONY_CONSOLE) doctrine:database:create
	$(SYMFONY_CONSOLE) doctrine:migrations:migrate --if-exists --no-interaction

## —— database ———————————————————————————————————————————————————————————————
migration: ## Reset the database
	$(SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction
