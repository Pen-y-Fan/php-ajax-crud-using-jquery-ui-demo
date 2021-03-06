# PHP AJAX CRUD using jQuery UI Demo

!["php crud using jQuery"](./doc/php-ajax-crup-app-with-smily.png "Example CRUD app")

The original source:
[WebsLesson PHP Ajax Crud using JQuery UI Dialog](https://www.webslesson.info/2018/03/php-ajax-crud-using-jquery-ui-dialog.html)

This is a PHP AJAX CRUD app. It performs basic CRUD operations, using jQuery Ajax calls, to a PHP backend.

The idea is to update the app to resemble an MVC framework.

## Requirements

- PHP 7.3+
- Apache
- MySQL

This set up can be created, using the provided **Dockerfile**, **docker-compose** and **Makefile**.

## Installation

The project can be installed in several ways. As this a personal project can be cloned, forked or downloaded using zip.
e.g. using git clone

```sh
git clone git@github.com:Pen-y-Fan/php-ajax-crud-using-jquery-ui-demo.git 
```

For other ways see [github docs](https://docs.github.com/en/github/using-git/which-remote-url-should-i-use).

## Setup

The easiest way it to use the provided Makefile. This will spin up the Docker environment, all the default setting have
been configured for you. You can set up the project with a local configuration.

### Docker

The first time the repo is clone, a new docker build the image is required to be built, this only needs to be done once.

```shell
make build-image
```

The table can be built, seeded with three records, these can be deleted as required.

```shell
make seed
```

Then the project can be started

```sh 
make up
```

You can check if you have make installed by running `make --version`

Without make the following docker-compose commands can be used:

```sh
docker-compose run -u 100:101 ajaxcrud php build_db.php
docker-compose up --remove-orphans -d
```

### Local configuration

Alternatively, you can set up your own Apache, PHP and MySQL server. The **.env.example** file can be copied:

```shell
cp .env.example .env
```

Edit the **.env** file and add you database settings. You do not need to manually create the database.

## Configuring the database

If you are using **Docker** with the supplied **docker-config** you do not need to alter any settings for the database.

The database, table and sample data can be created by following one of the following three methods:

- Use the make script

```sh
make seed
```

- Or the docker command:

```sh
docker-compose exec -u 100:101 ajaxcrud php build_db.php
```

- Or use PHP locally:

```shell
php build_db.php
```

You should see output like:

```text
Building db
Database database created OK
Creating table tbl_sample
Table tbl_sample created
Adding sample data
3 names added to table
```

Note: Rerunning the build command will add the 3 names again. It will not drop the table or database, if they already
exist.

Open your browser to <http://localhost:8080> and view the app.

### XDubug

Should the app need to be debugged, the parallel website is available on port **8081**. Open your browser to
<http://localhost:8081> and view the app. Point your debugging tools to use docker-compose **ajaxcrudx** service.

## Cypress testing

On Linux, the first time you run Cypress it will need to register with **xhost**, run the following command:

```shell
make cypress-register
```

Which will run the following docker command:

```shell
xhost +local:`docker inspect --format='{{ .ContainerConfig.Hostname }}' cypress/included:6.6.0`
```

Cypress tests can be run, once the system is registered once and is running `make up`, you can run the Cypress tests.

```shell
make cypress
```

This runs the following docker command, for Linux, which will run the Cypress docker container. It includes Firefox and
will display on the host monitor!

```shell
docker run -it --rm -d -v ./:/e2e -v /tmp/.X11-unix:/tmp/.X11-unix --net=host -w /e2e \ 
  -e DISPLAY --entrypoint cypress cypress/included:6.6.0 open --project .
```

If the system is set up locally, update cypress/integration/**end_to_end_spec.js** with the web address e.g.

- from: `cy.visit('http://localhost:8080/');`
- to: `cy.visit('http://php-ajax-crud-using-jquery-ui-demo.local:8080/');`

Then run with Node and NPM

```shell
npm install
npx cypress run
```

## TODO

- [x] Add Docker, docker-compose and Makefile to allow the app to be easily started and stopped
- [x] Set up the database
- [x] Move the database config out of the public directory
- [x] Separate the css and js from the html
- [x] Add the README.md and LICENSE
- [x] Create GitHub repo
- [x] Add composer
- [x] Add tooling: ECS, PHPUnit, PhpStan and Rector (jakzal/phpqa:1.50-php7.4-alpine)
- [x] Test SQLite in memory DB
- [x] Add CRUD tests using SQLite in memory DB
    - [x] refactor PHP files for testing environment (add **config** to **.env**)
- [x] Refactor PHP files to classes
    - [x] Add namespacing and auto-loading
- [x] Move jQuery script out of index.html into own file in js directory
- [x] Refactor PHP classes to API endpoints
- [x] Refactor PHP to return JSON, move view logic from PHP files into jQuery
- [x] Use the PDO prepared statements in the model class
- [x] Refactor jQuery (script.js)
- [x] Refactor API endpoints to only return JSON and update Tests
- [x] Add Cypress tests
- [x] Add validation and Tests for the PeopleController

## License

See [MIT license](./LICENSE.md).
