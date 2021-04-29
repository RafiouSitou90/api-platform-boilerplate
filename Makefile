DC=docker-compose

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