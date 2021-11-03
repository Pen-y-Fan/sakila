# Sakila practice

This project has been created to practice query builder using
the [Sakila MySQL database](https://dev.mysql.com/doc/sakila/en/). It is based on the YouTube
series [Laravel Query Builder by codemystif](https://www.youtube.com/watch?v=AGT8bCde8XU&list=PLkyrdyGDWthC-yd9n8R3CEauJC4sFl-kj)

## Requirements

This is a Laravel 8 project. The installation is similar to a new Laravel project.

- [PHP 7.3 or 7.4 or 8.0+](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org)

Recommended:

- [Git](https://git-scm.com/downloads)

## Clone

See [Cloning a repository](https://help.github.com/en/articles/cloning-a-repository) for details on how to create a
local copy of this project on your computer.

e.g.

```sh
git clone git@github.com:Pen-y-Fan/sakila.git
```

### Install the Dependencies

Install all the dependencies using composer

```sh
cd sakila
composer install
```

### Create an .env file

Create an `.env` file from `.env.example`

```shell script
composer post-root-package-install
```

### Generate an APP_KEY

```shell script
php artisan key:generate
```

## Install the Database

View [Sakila Sample Database / Installation](https://dev.mysql.com/doc/sakila/en/sakila-installation.html) for detailed
instructions on how to download and install the Sakila database.

## Configure Laravel database settings

Once the Sakila database has been created on your MySQL server, configure the Laravel **.env** file with the database,
updating username and password as per you local setup.

```text
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sakila
DB_USERNAME=YourDatabaseUserName
DB_PASSWORD=YourDatabaseUserPassword
```

## Run all tests

To make it easy to run all the PHPUnit tests a composer script has been created in composer.json. From the root of the
projects, run:

```shell script
composer tests
```

## Contributing

This is a **personal project**. Contributions are **not** required. Anyone interested in developing this project are
welcome to fork or clone for your own use.

## Credits

* [Michael Pritchard \(AKA Pen-y-Fan\)](https://github.com/pen-y-fan).

## License

MIT License (MIT). Please see [License File](LICENSE.md) for more information.

The contents of the **sakila-schema.sql** and **sakila-data.sql** files are licensed under the New BSD license. 
