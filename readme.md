# netscan readme
## Description

netscan is a nmap based tool to monitor usage of IPv4-Adresses in networks.

## Installation
This Description will use Debian 9 for examples. Packages and commands may be called otherwise on your system.

Required packages:
* nmap
* git
* [npm](https://www.npmjs.com/)
* [Composer](https://getcomposer.org/)
* PHP 7.1+
* Webserver

Also, your webserver has to support URL rewriting. Please check the internet for additional information.

1. First, download the sourcecode and change ownership to webserver-User.

   ``git pull https://github.com/pasterntt/netscan.git /var/www/html/``
   
   ``chown -R www-data:www-data /var/www/html/``

2. Next, install dependencies:

    ``composer install``
    
    ``npm install``

3. Prepare .env
    
    ``cp .env.example .env``
    
    Change `DB_HOST`, `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD` to the details you need. Also,
    you need to change `QUEUE_DRIVER` to `database`

4. Migrate database and prepare application
    
    ``php artisan migrate``
    
    ``php artisan passport:install --force``
    
    ``php artisan key:generate``
    
5. Change secret for logins
    
    Edit the file `ressources/assets/js/functions/auth/login.vue` and change the variable `secret` 
    to the key you recieved in step 4 with ID 2.

6. Compile Javascript
    
    ``npm run production``
    
7. Set up workers

    In this project, there is a supervisor-example Configuration file. You may need to change the paths.
    

## Used software

This Project is written with the help of:

* [Laravel](https://laravel.com)
* [VueJS](https://vuejs.org/)
* [AT-UI](https://at-ui.github.io/at-ui/)

## Questions & Support
Please us the GitHub-Tracker if you have questions.