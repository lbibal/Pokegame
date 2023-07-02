# pokegame
Symfony 6.3.* => php 8 >

firtly you have to create a db named by webtp8
configure your .env for connection to the db
if any pblm verify the port 
php bin/console doctrine:schema:update --force 
composer install
symfony server:start 
symfony server:stop
