# Laravel Module HMVC

Laramodule is a package for the Laravel framework that allows developers to organize and manage modules within their web application. It is designed to make it easy to create, manage and reuse modular components within a Laravel application.

With Laramodule, developers can create new modules that can be easily added to their application, and can also manage existing modules. It also allows developers to keep their code organized and maintainable, by separating different functionality into different modules. Each module can include its own controllers, views, routes, and other components.

Laramodule also provides an easy to use API for interacting with modules and their components. This allows developers to perform tasks such as enabling or disabling modules, and accessing module specific data or functionality.

Laramodule is a powerful tool for developers who want to create modular and maintainable applications with Laravel. It can help developers to speed up development, improve code quality and maintain a cleaner structure of their application.

To install through Composer, by run the following command:

```bash
$ composer require hexters/laramodule
```


## Autoloading
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

## Managing assets

Install node module pacakge
```bash
npm install -s @hexters/ladmin-vite-input
```

Open `vite.config.js` in your project and follow the instruction below.

```js

. . . 
import ladminViteInputs from '@hexters/ladmin-vite-input'
. . .

export default defineConfig({
    plugins: [
        laravel({
            input: ladminViteInputs([
                'resources/css/app.css',
                'resources/js/app.js'
            ]),
            refresh: true,
        }),
    ],
});


```


## Artisan
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

# Donate
If this Laravel package was useful to you, please consider donating some coffee ☕☕☕☕

- Patreon : [**hexters**](https://www.patreon.com/hexters)
