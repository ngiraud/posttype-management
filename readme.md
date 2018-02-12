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

### Columns
The default columns added to a post type are these ones :

```php
$table->increments('id');
$table->unsignedInteger('user_id');
$table->unsignedInteger('parent_id')->nullable();
$table->string('name');
$table->string('slug');
$table->unsignedTinyInteger('status');
$table->text('excerpt')->nullable();
$table->longText('content')->nullable();
$table->timestamp('published_at')->nullable();
$table->timestamps();
$table->softDeletes();
```

As you can see, the package uses softDeletes columns.

The user_id column is automatically saved in the model boot event saving with the authenticated user id.

The published_at column is automatically saved as well as the user_id if the status is published.

## Default comportment
Post types queries have a global scope to only fetch published data.

A relationship exists between a user and the posttype called owner().
Two relationships exist between a posttype parent and a posttype children :
* The first one is parent();
* The second one is children();

A rules public function exists, you can override it if you want.

To add another status you just have to add a constant in the model called STATUS_MYNEWSTATUS :

A ruleStatus public function exists, this is the rule for the status column, you will have to override it to add you new status.

```php
class Test extends PostType
{
    const STATUS_INCOMING = 3;

    public function ruleStatus()
    {
        return 'in:' . implode(',', [self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_INCOMING]);
    }
}
```

@TODO : automatically get the statuses to implement the method

## Credits

* Nicolas Giraud

This is my first real package, so please be indulgent with me :)