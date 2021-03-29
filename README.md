# BIND9 importer

This script imports domain names from an external database and generates a BIND9 compatible file.

## Installation

1. Download the latest master branch
2. Run `composer install`
3. Run `php artisan key:generate`
4. Create an .env file based on .env.example. The SQLite database is selected by default.
5. Run `php artisan migrate`

## Adding a server

Run `php artisan ljpc:add-server` to interactively add a server to the database.

## Connecting to BIND9

1. Setup a cronjob for `php artisan schedule:run` at every minute, this will create/update the file storage/zonefile.db
   every minute.
2. Make sure the file storage/zonefile.db is readable by the bind user.
3. Change the `BIND9_ZONE_FILE_DIRECTORY` property in the .env file to the folder where the slave files are placed.
4. Include the storage/zonefile.db file in /etc/bind/named.conf

