# Laravel HMVC

[![Latest Stable Version](https://poser.pugx.org/hexters/laramodule/v/stable)](https://packagist.org/packages/hexters/laramodule)
[![Total Downloads](https://poser.pugx.org/hexters/laramodule/downloads)](https://packagist.org/packages/hexters/laramodule)
[![License](https://poser.pugx.org/hexters/laramodule/license)](https://packagist.org/packages/hexters/laramodule)

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
And make `Modules` directory in your root project folder

```bash
mkdir Modules
```

* don't forget to run `composer dump-autoload` afterwards

## Managing assets

Install node module pacakge
```bash
npm install -D @hexters/ladmin-vite-input
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

Install node dependencies in the modules folder
```bash
cd Modules/Blog && npm install
```

Back to the root project and run vite
```bash
npm run dev
```

## Artisan
```bash
php artisan module:make Blog --command=OPTIONAL
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

# Inertia Support

> For inertia support will be coming soon, stay tuned for updates. And follow me in [@Hexters](https://twitter.com/hexters)


Laramodule is already supported for integration with InertiaJs. Follow the official Inertia.js website to see the installation steps. [inertiajs.com](https://inertiajs.com)

## Inertia with VueJs
Follow the command below to create a module with inertia support.

```bash
php artisan module:make Blog --command=inertia:init-vue
```

You can also do this with an existing module, but remember that. The `route.php` file will be replaced by a new file.

```bash
php artisan inertia:init-vue --module=Blog
```

Create a new javascript file in your root project directory with name `inertia.js` and paste code below.
```js
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

const getPage = (name) => {

    let names = (name).split('::');
    let module = names.shift();
    let page = names.pop();

    const pages = import.meta.glob('./Modules/**/*.vue', { eager: true })
    return pages[`./Modules/${module}/Resources/pages/${page}.vue`]
}

createInertiaApp({
    resolve: getPage,
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})

```

Add `inertia.js` to `vite.config.js`

```js
. . . 

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ladminViteInputs([
                'resources/css/app.css',
                'resources/js/app.js',

                'inertia.js', // <--- add here

            ]),
            refresh: true,
        }),
    ],
});
```

Open your `app.blade.php` and change vite load assets. 
```html

. . . 

 <head>

    @vite(['inertia.js', 'resources/css/app.css'])

 </head>
 
. . . 

```

## Tailwind Setup

For Tailwindcss, you need a few changes in `tailwind.config.js`

```js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [

    . . .

    "./Modules/**/*.blade.php",
    "./Modules/**/*.js",
    "./Modules/**/*.vue", // if you use VueJs
    "./Modules/**/*.jsx", // if you use React

    . . .

  ],
  
  . . . 

```

# Donate
If this Laravel package was useful to you, please consider donating some coffee ☕☕☕☕

- Patreon : [**hexters**](https://www.patreon.com/hexters)
