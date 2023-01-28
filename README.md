# Laravel Module

To install through Composer, by run the following command:

```bash
$ composer require hexters/laramodule
```


### Autoloading
By default the module classes are not loaded automatically. You can autoload your modules using `psr-4`. For example :
```json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Modules\\": "Modules/",
      "Database\\Factories\\": "database/factories/",
      "Database\\Seeders\\": "database/seeders/"
    }
  }
}
```
* don't forget to run `composer dump-autoload` afterwards

### Artisan
```bash
php artisan module:make Blog
php artisan module:make-cast BlogCast --module=Blog
php artisan module:make-channel BlogChannel --module=Blog
php artisan module:make-command BlogCommand --module=Blog
php artisan module:make-component BlogComponent --module=Blog
php artisan module:make-controller BlogController --module=Blog
php artisan module:make-event BlogEvent --module=Blog
php artisan module:make-exception BlogException --module=Blog
php artisan module:make-factory BlogFactory --module=Blog
php artisan module:make-job BlogJob --module=Blog
php artisan module:make-listener BlogListener --module=Blog
php artisan module:make-mail BlogMail --module=Blog
php artisan module:make-middleware BlogMiddleware --module=Blog
php artisan module:make-migration BlogMigration --module=Blog
php artisan module:make-model BlogModel --module=Blog
php artisan module:make-notification BlogNotification --module=Blog
php artisan module:make-observer BlogObserver --module=Blog
php artisan module:make-policy BlogPolicy --module=Blog
php artisan module:make-provider BlogProvider --module=Blog
php artisan module:make-request BlogRequest --module=Blog
php artisan module:make-resource BlogResource --module=Blog
php artisan module:make-rule BlogRule --module=Blog
php artisan module:make-scope BlogScope --module=Blog
php artisan module:make-seeder BlogSeeder --module=Blog
php artisan module:make-test BlogTest --module=Blog
php artisan module:publish Blog
```

Happy coding ☕
