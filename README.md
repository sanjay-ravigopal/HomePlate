HOW TO RUN (WINDOWS)

INSTALL PHP: 
https://www.php.net/downloads.php
Unzip to C:\php (or any folder).

Run the following in Powershell admin: 
setx PATH "$($env:PATH);C:\php"

Copy php.ini-development → php.ini

INSTALL COMPOSER:
https://getcomposer.org/Composer-Setup.exe

THEN run 
php --ini
to see where php.ini. Open "php.ini" in notepad and uncomment 
extension=pdo_mysql
extension=mysqli
extension=openssl
extension=mbstring
extension=curl
extension=fileinfo
extension=zip
extension=gd
extension=sodium

which will look like 
";extension=fileinfo" -> extension=fileinfo

THEN clone this repo. 

cd into it. 
run "composer install"

then "cp .env.example .env" (creates env file that you can later change)


then "php artisan key:generate"
then "php artisan serve"


