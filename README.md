# Sakila practice

This project has been created to practice Laravel models and uses Eloquent query builder. It is based on the
[Sakila MySQL database](https://dev.mysql.com/doc/sakila/en/). This Laravel version doesn't require the original sakila
database to be downloaded and installed, instead migrations and seeders are used to create the same database structure.
Not all the of Sakila database has been migrated, views, stored procedures and triggers are not re-created.

The concept for this project is based on the YouTube
series [Laravel Query Builder by codemystif](https://www.youtube.com/watch?v=AGT8bCde8XU&list=PLkyrdyGDWthC-yd9n8R3CEauJC4sFl-kj)
.

The **sakila-original** branch hosts the original concept for this project, it does require the Sakila database to be
downloaded and installed. The original database is not compatible with this Laravel version.

## Notable changes

Although the Laravel version is based on the original database, the structure and data is largely the same, these are
differences. [Laravels Eloquent Model Conventions](https://laravel.com/docs/8.x/eloquent#eloquent-model-conventions)
have been followed to create the models and database tables. Notable changes:

- The database tables have been renamed to **Eloquent Model Conventions** (e.g. table **store** is now **stores**, **
  film** is now **
  films**)
- The table id was **<table_name>_id** is now **id**, e.g. `store`.`store_id` is now `store`.`id`
- the **address.location** geometry data was not migrated. Although the **location** field does exist, it is a geometry
  field type, however it is empty.
- all **ids** and **foreign ids** are now unsigned big integers **int(20)**.

## Requirements

This is a Laravel 9 project. The requirements are the same as a
new [Laravel 9 project](https://laravel.com/docs/8.x/installation).

- [8.0+](https://www.php.net/downloads.php)
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

## Install

Install all the dependencies using composer

```sh
cd sakila
composer install
```

## Create .env

Create an `.env` file from `.env.example`

```shell script
composer post-root-package-install
```

## Generate APP_KEY

Generate an APP_KEY using the artisan command

```shell script
php artisan key:generate
```

## Configure Laravel

This experiment uses models and seeders to generate the tables for the database. Tests will use the seeded data, which
is based on the Sakila database. configure the Laravel **.env** file with the **database**, updating **username** and
**password** as per you local setup.

```text
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sakila
DB_USERNAME=YourDatabaseUserName
DB_PASSWORD=YourDatabaseUserPassword
```

## Create the database

The database will need to be manually created e.g.

```shell
mysql -u YourDatabaseUserName
CREATE DATABASE sakila CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit
```

Although Laravel is compatible with different database servers, some fields used in this database will only work on
MySQL servers.

## Install Database

This project uses models and seeders to generate the tables for the database. Tests will use the seeded data, which is
based on the Sakila database.

```shell
php artisan migrate
php artisan db:seed
```

## Run tests

To make it easy to run all the PHPUnit tests a composer script has been created in **composer.json**. From the root of
the projects, run:

```shell script
composer tests
```

You should see the results in testDoc format:

```text
PHPUnit 9.5.13 by Sebastian Bergmann and contributors.

Example (Tests\Unit\Example)
 ✔ Example

Example (Tests\Feature\Example)
 ✔ Example

Exploring Further The Where Keywords (Tests\Feature\ExploringFurtherTheWhereKeywords)
 ✔ Where in example
 ✔ Ten films with replacement cost between 1999 and 2099
 ✔ Ten films with replacement cost not between 1899 and 2099
 ✔ African egg or agent truman

Exploring Sub Queries Using Builder Query (Tests\Feature\ExploringSubQueriesUsingBuilderQuery)
 ✔ Display the titles of movies with the letters k and q
 ✔ Display the titles of movies with the letters k and q whose language is english

Find Overdue DVDs (Tests\Feature\FindOverdueDVDs)
 ✔ Display overdue d v ds

Actor (Tests\Feature\Models\Actor)
 ✔ There are two hundred actors
 ✔ First actor is penelope guiness
 ✔ Last actor is thora temple
 ✔ Last penelope guiness is in 19 films

Address (Tests\Feature\Models\Address)
 ✔ The first address is 47 my sakila drive
 ✔  47 my sakila drive is in lethbridge
 ✔ The last address is 1325 fukuyama street
 ✔  1325 fukuyama street is in tieli
 ✔  661 chisinau lane is address three hundred
 ✔ The first address is store one
 ✔ The third address is staff one
 ✔ The forth address is staff two
 ✔ The third address is staff one who works at address one
 ✔ The fifth address is customer mary smith

Category (Tests\Feature\Models\Category)
 ✔ The first category is action
 ✔ The last category is travel
 ✔ Action has sixty four films
 ✔ Action has amadeus holy and worst banger
 ✔ Long classic films with the actors

City (Tests\Feature\Models\City)
 ✔ The first city is la corua
 ✔ La corua is in spain
 ✔ The last city is ziguinchor
 ✔ Ziguinchor is in senegal
 ✔ Lethbridge is city six hundred
 ✔ Lethbridge has two addresses

Country (Tests\Feature\Models\Country)
 ✔ The first country is afghanistan
 ✔ Afghanistan has city of kabul
 ✔ The last country is zambia
 ✔ Zambia has a city called kitwe
 ✔ Japan is country fifty
 ✔ Japan has thirty one cities

Customer (Tests\Feature\Models\Customer)
 ✔ There are 599 customers
 ✔ The first customer is mary smith
 ✔ The last customer staff is austin cintron
 ✔ Mary smith address is 1913 hanoi way
 ✔ Mary smith store address is 47 my sakila drive
 ✔ Active customers count store one
 ✔ Customer one first rental is film patient sister
 ✔ Customer one first payment is 299
 ✔ Australian customer total spend
 ✔ The last payment off every customer
 ✔ The last ten paying customers
 ✔ The top ten paying customers
 ✔ Total spend
 ✔ Customer payments
 ✔ Customer from australia store
 ✔ Customer who watched action films
 ✔ Customer who rented zorro ark using where has
 ✔ Customer who rented zorro ark using joins and where
 ✔ Customers with outstanding rentals

Film (Tests\Feature\Models\Film)
 ✔ The first film is academy dinosaur
 ✔ The last film is zorro ark
 ✔ The academy dinosaurs language is english
 ✔ The academy dinosaurs categories are documentary
 ✔ The academy dinosaurs actors
 ✔ Academy dinosaurs has an inventory of eight
 ✔ The longest film is 185minutes
 ✔ The stores
 ✔ Bebecca rented academy dinosaurs last

Inventory (Tests\Feature\Models\Inventory)
 ✔ There are 4581 items of inventory
 ✔ The first inventory item is film academy dinosaur
 ✔ The first inventory item is store
 ✔ The 1000th inventory item is film desire alien
 ✔ The last inventory item is film zorro ark
 ✔ The first inventory was rented to customer joel francisco

Payment (Tests\Feature\Models\Payment)
 ✔ There are 16049 payments
 ✔ The first payment is migrated
 ✔ The last payment is migrated
 ✔ The 8000 payment is migrated
 ✔ The first payment is for customer 1 mary smith
 ✔ The first payment is for staff 1
 ✔ The first payment is for rental 76
 ✔ The sum o f all payments is 6741651
 ✔ Top customers percentage of spend

Rental (Tests\Feature\Models\Rental)
 ✔ The first rental is migrated
 ✔ The last rental is migrated
 ✔ The 8000 rental is migrated
 ✔ The first rental is for customer 130
 ✔ The first rental is for staff 1
 ✔ The first rental is for inventory 367
 ✔ The first rental payment is 299
 ✔ The first rental payment was for film blanket beverly
 ✔ The first rental payment was from store 1

Staff (Tests\Feature\Models\Staff)
 ✔ The first staff is mike hillyer
 ✔ The first staff is jon stephens
 ✔ Mike hillyer address is 23 workhaven lane
 ✔ Jon stephens address is 1411 lillydale drive
 ✔ Mike hillyer store address is 47 my sakila drive
 ✔ Jon stephens store address is 28 my sql boulevard
 ✔ Jon stephens first rental was inventory 2452 film 535 love suicides
 ✔ Mike hillyer first payment taken was 299
 ✔ Mike hillyer takings for june 2005

Store (Tests\Feature\Models\Store)
 ✔ The first store address
 ✔ The second store address
 ✔ The first store has manager staff one
 ✔ The second store has manager staff two
 ✔ The first store has 326 customers
 ✔ The second store has 326 customers
 ✔ The first store first customer is mary smith
 ✔ Active customers count by store
 ✔ Store inventory

Translating Raw Sql Query Using Query Builder (Tests\Feature\TranslatingRawSqlQueryUsingQueryBuilder)
 ✔ Raw sql to query builder

Understanding Joins In Sql And Translating Them In Query Builder (Tests\Feature\UnderstandingJoinsInSqlAndTranslatingThemInQueryBuilder)
 ✔ Laravel way to join models
 ✔ Joining staff to address to city to country

Understanding The Usage Of Where Keyword (Tests\Feature\UnderstandingTheUsageOfWhereKeyword)
 ✔ Operator defaults to equals
 ✔ Where clauses can be chained
 ✔ Where clause can be an array
 ✔ Where clause can be a closure
 ✔ Where with order by and group by

Using Joins And Conditionals In Query Builder (Tests\Feature\UsingJoinsAndConditionalsInQueryBuilder)
 ✔ Count of film categories

Writing AComplex Query Of Joining Results From Two Sub Queries (Tests\Feature\WritingAComplexQueryOfJoiningResultsFromTwoSubQueries)
 ✔ Display each store id city country and sales
```

## Log file

A log of the results from many of the tests are output to storage > logs > **laravel.log**, this is useful to see the
full output of the queries.

## UML diagram

A UML diagram in png format has been created in the docs folder. **Note:** this is based on the **original** sakila
database. See above for the **Notable changes**.

![UML diagram](docs/ulm-diagram.png "UML diagram")

## Contributing

This is a **personal project**. Contributions are **not** required. Anyone interested in developing this project are
welcome to fork or clone for your own use.

## Credits

- [Michael Pritchard \(AKA Pen-y-Fan\)](https://github.com/pen-y-fan).

## License

MIT License (MIT). Please see [License File](LICENSE.md) for more information.

The contents of the Sakila database, namely the **sakila-schema.sql** and **sakila-data.sql** files form the basis of
this project. The Sakila database is licensed under the New BSD license, the Sakila database does not need to be
downloaded. Laravel will migrate and seed the database.
