# Tourismtrends

## Requirements
- composer.phar
- mongodb
- php 5.5
- PHP MongoDB Driver

## Import demo data
- tourism-dataset.json
- ```mongoimport --db tourism --collection data --file primer-dataset.json```

## Up and Running
- install PHP MongoDB Driver(http://docs.mongodb.org/ecosystem/drivers/php/)
- configure your database in app/config/config.yml Doctrine MongoDB section
- install composer ```php -r "readfile('https://getcomposer.org/installer');" | php```
- execute ```php composer.phar install```
