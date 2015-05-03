composer require slim/slim
composer require "doctrine/mongodb-odm=~1.0.0-BETA10@dev"


sudo vi /etc/php5/cli/php.ini
    extension=mongo.so
sudo vi /etc/php5/apache2/php.ini
    extension=mongo.so
sudo service php5-fpm restart
composer require "doctrine/mongodb-odm=~1.0.0-BETA10@dev"
sudo apt-get update
sudo apt-get install php5-dev
sudo pecl install mongo

Cache
sudo pecl install apc OR sudo apt-get install php-apc
sudo service apache2 restart
