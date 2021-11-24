# SnowTricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/0649ea7d8fe94df5bad817154ca53897)](https://app.codacy.com/gh/PierreThiollent/SnowTricks?utm_source=github.com&utm_medium=referral&utm_content=PierreThiollent/SnowTricks&utm_campaign=Badge_Grade_Settings)

This project is carried out as part of my course PHP / Symfony Application Developer at OpenClassroom.

## Requirements 🔧

- Composer
- PHP (^8.0)
- Apache
- MySQL

### Setup 🔄

```
$ git clone
```

```
$ cd <project>
```

```
$ composer install
$ npm install
$ npm run build
```

### Config

In the project folder execute this command

```
$ cp .env .env.local
```

And then fill the DATABASE_URL variable

```
APP_ENV=dev
APP_SECRET=whatever
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
```

### DB setup

To create the database and all the tables execute this command :

```
$ php bin/console doctrine:database:create
```

You can use the db.sql file to populate your database

### Run

To run the development server execute this command :

```
$ symfony serve
```
