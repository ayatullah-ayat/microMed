# :Laravel Authorization


Authorization is the function of specifying access rights to resources related to information security in general and to access control in particular. More formally, "to authorize" is to define an access policy. For example, human resources staff is normally authorized to access employee records and this policy is usually formalized as access control rules in a computer system.

## Installation

_For Laravel 5.4+ installation details._

Add the package to your ```composer.json``` file

```
"Alauddin/Authorize": "0.1.*"
```

Add download link to your ```composer.json``` file
```
"repositories": [
    {
        "type": "git",
        "url": "https://gitlab.com/aso-nije-kori/laravel-auth.git"
    }
]
```

Install the package from composer:

```
composer update 
```

Add the service provider to your ```config/app.php``` file

```
Alauddin\Authorize\AuthorizeServiceProvider::class,
```

Publish the package file(s)

```
php artisan vendor:publish --provider="Alauddin\Authorize\AuthorizeServiceProvider" --force
```

Generating autoload files

```
composer dump-autoload 
```

If you haven't already set up Database connection then make the migration file and run the migration.

```
php artisan migrate
php artisan db:seed --class=RolesTableSeeder
```

Add ```Authorizable``` trait in ```Admin``` model.

```
<?php

namespace App;

use Alauddin\Authorize\Models\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Authorizable;
```

At this point you're all good to go. See Getting Started for how to use the package.

## Getting Started

### Accessing Package

By default all routes are prefixed with ```/authorize```.

* Users: ```/authorize/users```
* Roles: ```/authorize/roles```
* Permissions: ```/authorize/permissions```

You can change this prefix by editing ```route-prefix``` in ```config/authorization.php```.

```
'route-prefix' => 'authorize'
```

### Middleware

Authorization uses ```authorize``` for middleware. This allows logged in users with the ```admin``` role to access it.

If you wish to test out the system without middleware then go to ```config/authorization.php``` and set middleware to ```auth```.

```
'middleware' => 'auth',
```

### How to Use

You have to uses ```authorize``` middleware for getting control over your routes.

Go to your ```routes/web.php``` file and make a route group for all of your routes to which you wants to set access control.

```
//Place the global routes here which you do not need to set authorize
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('authorize')->group(function() {
    //Place all your routes here
});
```
