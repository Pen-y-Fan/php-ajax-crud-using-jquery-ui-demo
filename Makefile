# Usage:
# make up - starts the docker-compose in the same directory in demon (background)
# make down - stops the docker-compose
# make shell - opens a sh terminal in the running ajaxcrud container as a standard user
# make shell-root -  opens a sh in ajaxcrud container as root user
# make shell-web -  opens a sh in ajaxcrud container as www-data user
# make up-f - start the docker-compose in foreground (useful for error messages)
# make tests - run phpunit tests
# make test-coverage phpunit coverage html report will be created in build/coverage
# make phpstan - static analysis using phpstan
# make checkcode - check code using php_code sniffer (phpcbf)
# make fixcode - fix code using php_code sniffer (phpcbf)
# make check-cs - check code using easy coding standards (ecs)
# make fix-cs - fix code using easy coding standards (ecs)

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
	docker-compose exec -u ${shell id -u}:${shell id -g} ajaxcrud sh
build:
	 docker-compose exec -u ${APACHE_UID}:${APACHE_GID} ajaxcrud php /app/build_db.php
shell-run:
	docker-compose run -u ${shell id -u}:${shell id -g} ajaxcrud sh
shell-root:
	docker-compose exec -u 0:0 ajaxcrud sh
shell-web:
	docker-compose exec -u ${APACHE_UID}:${APACHE_GID} ajaxcrud sh

chown:
	docker-compose exec -u $0:0 ajaxcrud chown -R ${shell id -u}:${shell id -g} ./

.PHONY : tests
tests:
	docker-compose exec -u ${shell id -u}:${shell id -g} ajaxcrud ./vendor/bin/phpunit


phpstan:
	docker run --init -it --rm -v $(shell pwd):/project -v $(shell pwd)/tmp-phpqa:/tmp -w /project \
		jakzal/phpqa:1.52-php7.4-alpine phpstan analyse

checkcode:
	docker run --init -it --rm -v $(shell pwd):/project -v $(shell pwd)/tmp-phpqa:/tmp -w /project \
    		jakzal/phpqa:1.52-php7.4-alpine phpcs public --standard=phpcs.xml

fixcode:
	docker run --init -it --rm -v $(shell pwd):/project -v $(shell pwd)/tmp-phpqa:/tmp -w /project \
    		jakzal/phpqa:1.52-php7.4-alpine phpcbf public --standard=phpcs.xml

check-cs:
	docker run --init -it --rm -v $(shell pwd):/project -v $(shell pwd)/tmp-phpqa:/tmp -w /project \
    		jakzal/phpqa:1.52-php7.4-alpine ecs check

fix-cs:
	docker run --init -it --rm -v $(shell pwd):/project -v $(shell pwd)/tmp-phpqa:/tmp -w /project \
    		jakzal/phpqa:1.52-php7.4-alpine ecs check --fix

grumphp:
	docker run --init -it --rm -v $(shell pwd):/project -v $(shell pwd)/tmp-phpqa:/tmp -w /project \
			jakzal/phpqa:1.52-php7.4-alpine ./vendor/bin/grumphp run

toolbox:
	docker run --init -it --rm -v $(shell pwd):/project -v $(shell pwd)/tmp-phpqa:/tmp -w /project jakzal/phpqa:1.52-php7.4-alpine sh

# jakzal/phpqa:1.50-php8.0-alpine
