# Laravel HMVC V4

[![Latest Stable Version](https://poser.pugx.org/hexters/laramodule/v/stable)](https://packagist.org/packages/hexters/laramodule)
[![Total Downloads](https://poser.pugx.org/hexters/laramodule/downloads)](https://packagist.org/packages/hexters/laramodule)
[![License](https://poser.pugx.org/hexters/laramodule/license)](https://packagist.org/packages/hexters/laramodule)

Laramodule is a package for the Laravel framework that allows developers to organize and manage modules within their web application. It is designed to make it easy to create, manage and reuse modular components within a Laravel application.

With Laramodule, developers can create new modules that can be easily added to their application, and can also manage existing modules. It also allows developers to keep their code organized and maintainable, by separating different functionality into different modules. Each module can include its own controllers, views, routes, and other components.

Laramodule also provides an easy to use API for interacting with modules and their components. This allows developers to perform tasks such as enabling or disabling modules, and accessing module specific data or functionality.

Laramodule is a powerful tool for developers who want to create modular and maintainable applications with Laravel. It can help developers to speed up development, improve code quality and maintain a cleaner structure of their application.



## Doc. Versions
|Version|Doc.|
|-|-|
|V3|[Read Me](https://github.com/hexters/laramodule/blob/main/src/releasedoc/v3.md)|

To install through Composer, by run the following command:

```bash
composer require hexters/laramodule
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

Don't forget to run the commands below

```bash
composer dump-autoload
```

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

Install the node module package in all module folders
```bash
php artisan module:npm --install
php artisan module:npm --update
```

And run the vite command below

```bash
npm run dev

npm run build
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

# Livewire Support
Laramodule is already supported for integration with **Livewire**

### [Documentation](https://github.com/hexters/wirehmvc)


# Inertia Support

Laramodule is already supported for integration with InertiaJs. Follow the official Inertia.js website to see the installation steps. [inertiajs.com](https://inertiajs.com)

## Inertia with VueJs

Follow the command below to create a module and select `Inertia With VueJs` in preset options.
```bash
php artisan module:make Blog
```

You can run the command initialize inertia with vue for an existing module as below, but remember that. The `route.php` file will be replaced by a new file.

```bash
php artisan module:inertia-vue --module=Blog
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

    . . . 

    @vite(['inertia.js', 'resources/css/app.css'])

    . . .

 </head>
 
. . . 

```

## Inertia with React

Follow the command below to create a module and select `Inertia With ReactJs` in preset options.
```bash
php artisan module:make Blog
```
You can run the command initialize inertia with react for an existing module as below, but remember that. The `route.php` file will be replaced by a new file.

```bash
php artisan module:inertia-react --module=Blog
```
Create a new javascript file in your root project directory with name `inertia.jsx` and paste code below.
```js
import React from 'react'
import { createInertiaApp } from '@inertiajs/react'
import { createRoot } from 'react-dom/client'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'


const getPage = (name) => {

    let names = (name).split('::');
    let module = names.shift();
    let page = names.pop();

    return resolvePageComponent(
        `./Modules/${module}/Resources/pages/${page}.jsx`,
        import.meta.glob('./Modules/**/*.jsx', { eager: true })
    );
}

createInertiaApp({
    resolve: getPage,
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />)
    },
})

```
Add `inertia.jsx` to `vite.config.js`

```js
. . . 
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        react(),
        laravel({
            input: ladminViteInputs([
                'resources/css/app.css',
                'resources/js/app.js',

                'inertia.jsx', // <--- add here

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

    . . . 

    @viteReactRefresh
    @vite(['inertia.jsx', 'resources/css/app.css'])

    . . . 

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

    "./Modules/**/Resources/**/*.{blade.php,js,vue}",
    "./resources/**/*.{blade.php,js}",

    . . .

  ],
  
  . . . 

```

## Helpers

Module path

```php
module_path('Blog');

module_path('Blog', 'target/path');
```

list of all module paths

```php
module_path_lists();
```
list of all module paths

```php
module_name_lists();
```

Enable Module
```php
module_enable('Blog')
```

Disable Module
```php
module_disable('Blog')
```

Get module status

```php
module_status('Blog')
```

Grouping module by status

```php
module_group_status()
```
View module details

```php
module_details('Blog')
```

Get active module

```php
module_active('Blog')
```
# Events

Laramodule has two events when enabling and disabling the module that can be listened to for specific purposes.

You can read how to use events & listeners in the [official documentation.](https://laravel.com/docs/master/events#registering-events-and-listeners)

```php

use Hexters\Laramodule\Events\ModuleDisabled;
use Hexters\Laramodule\Events\ModuleEnabled;

/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    ModuleDisabled::class => [
        // ...
    ],
 
    ModuleEnabled::class => [
        // ...
    ],
];
```



# Supporting the project
You can support the maintainer of this project through the referral links below
- [**Sign up for DigitalOcean**](https://www.digitalocean.com/?refcode=36844cd4f4b4&utm_campaign=Referral_Invite&utm_medium=Referral_Program&utm_source=badge)
- [**PayPal**](https://paypal.me/asepss19)

Follow package updates on my X [@hexters](https://twitter.com/hexters)
