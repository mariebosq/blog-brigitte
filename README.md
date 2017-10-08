blog-brigitte
=============

A Symfony project created on August 28, 2017, 7:39 pm.


Requirements
- PHP 7.0
- MySQL

Installation
- composer install
- php bin/console ckeditor:install

Migrations
- php bin/console doctrine:database:create
- (Générer les migrations à partir des entities) php bin/console doctrine:migrations:diff
- (lancer les migrations) php bin/console doctrine:migrations:migrate


