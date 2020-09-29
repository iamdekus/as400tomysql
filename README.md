# AS400 to MySQL

Have you ever had the need to trasport your AS400 data into a MySQL database? No? Well maybe you will. Like me. That's because i wrote this simple script where thanks a simple patterns system i can easily import data from an exported file.

## How to use it?

Before to use the script you need to install [Medoo](https://github.com/catfan/Medoo) via [Composer](https://github.com/composer/composer).

```
$ composer require catfan/medoo
```

And update the composer
```
$ composer update
```
Now it's time to config the script.

## How to change the config?

Fill the database datas by putting the database name, username and the password. :floppy_disk:

```
$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'db_name',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '',
    'option' => [
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ],
]);
```

Inside the script self there are two constants. They manage the paths where the script get the files and where they are moved after they are processed. :mag:

```
$PATH_FILE_TO_PROCESS = 'FileDaElaborare';
$PATH_FILE_PROCESSED = 'FileElaborati';
```

