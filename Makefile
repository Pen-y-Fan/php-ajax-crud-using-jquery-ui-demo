# Usage:
# make up - starts the docker-compose in the same directory in demon (background)
# make down - stops the docker-compose
# make shell - opens a sh terminal in the running ajaxcrud container as a standard user
# make shell-root -  opens a sh in ajaxcrud container as root user
# make shell-web -  opens a sh in ajaxcrud container as www-data user
# make up-f - start the docker-compose in foreground (useful for error messages)

# To check your user ID run echo $(id -u)
UID = 1000

down:
	docker-compose down --remove-orphans
shell:
	docker-compose exec -u ${UID}:${UID} ajaxcrud sh
shell-run:
	docker-compose run -u ${UID}:${UID} ajaxcrud sh
shell-root:
	docker-compose exec ajaxcrud sh
shell-web:
	docker-compose exec -u 33:33 ajaxcrud sh
up:
	docker-compose up --build --remove-orphans -d
up-f:
	docker-compose up --build --remove-orphans
.PHONY : tests
tests:
	docker-compose exec ajaxcrud vendor/bin/phpunit
	docker-compose exec ajaxcrud chown -R ${UID}:${UID} ./
	docker-compose exec ajaxcrud chown -R 33:33 ./public/
lint:
	docker-compose exec -u ${UID}:${UID} ajaxcrud vendor/bin/phpcs app/src app/tests
lint-clean:
	docker-compose exec -u ${UID}:${UID} ajaxcrud vendor/bin/phpcbf app/src app/tests
flush:
	docker-compose exec ajaxcrud vendor/bin/phpunit app/tests '' flush=1
	docker-compose exec ajaxcrud chown -R ${UID}:${UID} ./
	docker-compose exec ajaxcrud chown -R 33:33 ./public/

chown:
	docker-compose exec ajaxcrud chown -R ${UID}:${UID} ./
	docker-compose exec ajaxcrud chown -R 33:33 ./public/
