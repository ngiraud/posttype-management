# PostType Management

This package allows you to add or remove a simple "posttype" configuration to your Laravel application.

## Installation

### Prerequisites

* This package can be used in Laravel 5.4 or higher

### Step 1

You can install the package via composer:

```bash
composer require ngiraud/posttype-management
```

### Step 2

In Laravel 5.5 the service provider will automatically get registered. In older versions of the framework just add the service provider in config/app.php file:

```php
'providers' => [
    // ...
    NGiraud\PostType\PostTypeServiceProvider::class,
];
```

## Usage

### Create a post type

This command will create a model, resource controller, migration and factory into your Laravel project.

It will also add a route in the routes/web.php file.

```bash
php artisan posttype:create MYPOSTTYPE [-m|--migrate] [--ctrl-folder=MYFOLDER]
```

If you want to specify a custom path to your Controller directory inside the App/Http, you can use :
```bash
php artisan posttype:create MYPOSTTYPE --ctrl-folder=MYFOLDER
```

The route will be namespaced with the folder name.

If you want to migrate the generate migration instantly :
```bash
php artisan posttype:create MYPOSTTYPE -m
```

### Remove a post type

This command will remove the model, resource controller, migration and factory from your Laravel project.

```bash
php artisan posttype:remove MYPOSTTYPE
```

BE CAREFUL : The associated route will not be removed!

## Credits

* Nicolas Giraud

This is my first real package, so please be indulgent with me :)