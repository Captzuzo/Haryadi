# Haryadi Framework

A lightweight PHP MVC framework.

## Requirements

- PHP 8.0 or higher
- Composer
- MySQL/MariaDB

## Installation

```bash
composer create-project haryadi/haryadi-framework myapp
cd myapp
cp .env.example .env
```

### Install as a Composer package (for other projects)

The package is configured as a Composer library. To test installing it into another project locally you can use Composer's `path` repository which will place the package under `vendor/haryadi/haryadi-framework`.

Example (from the `examples/demo` directory provided in this repository):

```bash
cd examples/demo
composer install
php -S localhost:8001 -t public
```

This demo uses a `path` repository in its `composer.json` that points to the parent folder (the framework source). Composer will create a symlink in `examples/demo/vendor/haryadi/haryadi-framework` to this repository when you run `composer install` in the demo.

If you want to publish the package to Packagist so other projects can `composer require haryadi/haryadi-framework`, register the package at https://packagist.org and ensure the repository is public (or use your private repository/mirror).

## Configuration

1. Edit `.env` file with your database credentials and application settings
2. Make sure the `storage` and `public` directories are writable

## Directory Structure

```
myapp/
├── app/
│   ├── Controllers/
│   └── Models/
├── config/
├── public/
│   └── index.php
├── resources/
│   └── views/
├── routes/
│   └── web.php
├── src/
│   └── Haryadi/
├── storage/
│   ├── logs/
│   └── cache/
├── .env
├── .env.example
└── composer.json
```

## Basic Usage

### Creating a Controller

```php
namespace App\Controllers;

use Haryadi\Controller\BaseController;

class UserController extends BaseController
{
    public function index()
    {
        $this->view('users.index', [
            'users' => ['John', 'Jane']
        ]);
    }
}
```

### Defining Routes

```php
use App\Controllers\UserController;
use Haryadi\Routing\Route;

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
```

### Creating a View

Create a new file in `resources/views/users/index.haryadi.php`:

```php
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
</head>
<body>
    <h1>Users List</h1>
    <ul>
    <?php foreach ($users as $user): ?>
        <li><?= $user ?></li>
    <?php endforeach; ?>
    </ul>
</body>
</html>
```

### Creating a Model

```php
namespace App\Models;

use Haryadi\Database\Model;

class User extends Model
{
    protected static string $table = 'users';
}
```

## Running the Application

1. Configure your web server to point to the `public` directory
2. For development, you can use PHP's built-in server:

```bash
php -S localhost:8000 -t public
```

## Contributing

Feel free to submit pull requests or create issues for bugs and feature requests.

## License

The Haryadi Framework is open-source software licensed under the MIT license.
