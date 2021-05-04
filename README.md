# Lumen PHP Framework

Used this tutorial as a guide: 
https://auth0.com/blog/developing-restful-apis-with-lumen/#Securing-the-Authors-API-with-Auth0

1) To create project, run from vagrant ssh:
composer self-update --1
composer create-project --prefer-dist laravel/lumen phptutorial
composer self-update --rollback

2) To create database migration table, run:
php artisan make:migration create_products_table

3) To run database migration:
php artisan migrate

4) To seed database:
php artisan make:seed
or 
php artisan db:seed --class=ProductsTableSeeder

) If you change the sites property in homestead.yaml, run
vagrant reload --provision