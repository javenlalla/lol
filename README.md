#Environment Setup
sudo apt-get update  
sudo apt-get install php5 libapache2-mod-php5 php5-mcrypt  
sudo apt-get install git  
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10  
echo "deb http://repo.mongodb.org/apt/ubuntu "$(lsb_release -sc)"/mongodb-org/3.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.0.list   
sudo apt-get update  
sudo apt-get install -y mongodb-org  
IP=192.210.241.185  
export IP  
echo $IP  

curl -sS https://getcomposer.org/installer | php  
mv composer.phar /usr/local/bin/composer  
composer install  

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

#Apache
To enable SEO-friendly URLs (removing index.php from the url), include the following within the <Directory> configuration of the vhost:
    <IfModule mod_rewrite.c>
        Options -MultiViews
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^(.*)$ index.php [QSA,L]
    </IfModule>

#Cache
sudo pecl install apc OR sudo apt-get install php-apc  
sudo service apache2 restart  

#After Cloning Repo
chown -R www-data:www-data  

composer install 

#Additional Notes
sudo rm /var/lib/mongodb/mongod.lock    
sudo service mongodb restart    
 
