init: create-local-files

create-local-files:
	@echo "Create local files"
	touch env_variables/app.env.local
	touch env_variables/db.env.local

build:
	@echo "Build project"
	docker compose build

force-rebuild:
	@echo "Build project"
	docker compose build --no-cache

progress-build:
	docker compose build --progress=plain

run:
	@echo "Run project"
	docker compose up

attach-app:
	@echo "Attach to app container"
	docker exec -it weatherfetch-app-1 bash

cache-clear:
	@echo "Build project"
	docker exec -it weatherfetch-app-1 php bin/console cache:clear

debug-router:
	@echo "Debug router"
	docker exec -it weatherfetch-app-1 php bin/console debug:router

make-migrations:
	@echo "Make migrations"
	docker exec -it weatherfetch-app-1 php bin/console make:migration

migrate:
	@echo "Migrate"
	docker exec -it weatherfetch-app-1 php bin/console doctrine:migrations:migrate --no-interaction


php-code-beautifier:
	@echo "Run php-code-beautifier"
	docker exec -it weatherfetch-app-1 php ./vendor/bin/phpcbf

php-cs-fixer:
	@echo "Run php-cs-fixer"
	docker exec -it weatherfetch-app-1 php vendor/bin/php-cs-fixer fix

php-stan:
	@echo "Run phpstan"
	docker exec -it weatherfetch-app-1 php vendor/bin/phpstan analyse

down:
	docker compose down

remove:
	docker-compose rm

clean:
	docker-compose down -v
