# Usage:
# make up - starts the docker-compose in the same directory in demon (background)
# make down - stops the docker-compose
# make shell - opens a sh terminal in the running ajaxcrud container as a standard user
# make shell-root -  opens a sh in ajaxcrud container as root user
# make shell-web -  opens a sh in ajaxcrud container as www-data user
# make up-f - start the docker-compose in foreground (useful for error messages)

# To check your user ID run echo $(id -u)
UID = 1000
GID = 1000
# apache:apache is 100:101
APACHE_UID = 100
APACHE_GID = 101


up:
	docker-compose up --build --remove-orphans -d
up-f:
	docker-compose up --build --remove-orphans
down:
	docker-compose down --remove-orphans
shell:
	docker-compose exec -u ${UID}:${GID} ajaxcrud sh
build:
	 docker-compose exec -u ${APACHE_UID}:${APACHE_GID} ajaxcrud php /app/build_db.php
shell-run:
	docker-compose run -u ${UID}:${UID} ajaxcrud sh
shell-root:
	docker-compose exec -u 0:0 ajaxcrud sh
shell-web:
	docker-compose exec -u ${APACHE_UID}:${APACHE_GID} ajaxcrud sh

.PHONY : tests
tests:
	docker-compose exec -u ${APACHE_UID}:${APACHE_GID} ajaxcrud vendor/bin/phpunit
lint:
	docker-compose exec -u ${APACHE_UID}:${APACHE_GID} ajaxcrud vendor/bin/phpcs app/src app/tests
lint-clean:
	docker-compose exec -u ${APACHE_UID}:${APACHE_GID} ajaxcrud vendor/bin/phpcbf app/src app/tests
