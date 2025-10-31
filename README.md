# Haryadi Framework (minimal Laravel-like MVC)

Haryadi is a small educational PHP MVC framework inspired by Laravel. It provides routing, controllers, a tiny view engine using `.haryadi.php` templates, basic request/response objects, a simple model base using PDO, error handling, helper functions, and a small CLI to generate controllers/models.

## Folder structure

```
myapp/
├─ app/
│  ├─ Controllers/
│  │  ├─ HomeController.php
│  │  └─ AuthController.php
│  └─ Models/
│     └─ User.php
├─ public/
│  └─ index.php        # Front controller
├─ resources/
│  └─ views/
│     └─ home.haryadi.php
├─ routes/
│  └─ web.php
├─ src/
│  └─ Haryadi/         # Framework core (Application, Kernel, Routing, Http, View, Models, etc.)
├─ bin/
│  └─ haryadi          # CLI script (make controllers/models)
├─ composer.json
├─ .env.example
└─ README.md
```

## composer.json

The project includes a `composer.json` using PSR-4 autoload for `Haryadi\` and `App\` namespaces. Key fields:

```json
{
  "name": "haryadi/haryadi-framework",
  "autoload": {
    "psr-4": {
      "Haryadi\\": "src/Haryadi/",
      "App\\": "app/"
    }
  },
  "bin": ["bin/haryadi"],
  "require": {
    "php": ">=7.4",
    "vlucas/phpdotenv": "^5.5"
  }
}
```

## Core files (high level)

- `src/Haryadi/Application.php` – small app container, basePath helper.
- `src/Haryadi/Kernel.php` – bootstraps helpers, error handler and loads `routes/web.php`.
- `src/Haryadi/Routing/Route.php` and `src/Haryadi/Routing/Router.php` – routing API and dispatcher.
- `src/Haryadi/Http/Request.php` and `src/Haryadi/Http/Response.php` – request/response helpers.
- `src/Haryadi/Controller/Controller.php` – base controller with `view()` helper.
- `src/Haryadi/View/View.php` – very small view engine that loads `resources/views/*.haryadi.php`.
- `src/Haryadi/Models/Model.php` – minimal PDO-backed base model using `env()`.
- `src/Haryadi/Exceptions/Handler.php` – basic error/exception handler.
- `src/Haryadi/Support/helpers.php` – global helper functions like `env()`, `view()`, `request()`, `response()` and `dd()`.

## Sample app files

- `routes/web.php`:

```php
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use Haryadi\Routing\Route;

Route::get('/', [HomeController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
```

- `app/Controllers/HomeController.php` returns the `home` view.
- `app/Controllers/AuthController.php` contains a simple `login` action.
- `app/Models/User.php` extends the base `Model`.
- `resources/views/home.haryadi.php` is the view template.

## Bootstrapping (`public/index.php`)

The front controller loads Composer's autoload, `.env` (via vlucas/phpdotenv), boots the kernel and dispatches the incoming request:

```php
require __DIR__ . '/../vendor/autoload.php';
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

$app = new Haryadi\Application(dirname(__DIR__));
$kernel = new Haryadi\Kernel($app);
$kernel->boot();

$request = Haryadi\Http\Request::capture();
$response = $kernel->handle($request);

if (method_exists($response, 'send')) {
    $response->send();
} else {
    echo (string) $response;
}
```

## CLI

There is a small CLI script at `bin/haryadi`.

Examples:

PowerShell / Windows:

```powershell
# install dependencies
composer install

# run built-in server
php -S localhost:8000 -t public;

# use the CLI to create a controller
php .\bin\haryadi buat:controller BlogController

# create a model
php .\bin\haryadi buat:model Post
```

On \*nix:

```bash
php bin/haryadi buat:controller BlogController
```

The CLI will create files under `app/Controllers` and `app/Models`.

## Installation (local development)

1. Clone or extract this scaffold into a folder `myapp`.
2. Run:

```powershell
composer install
php -S localhost:8000 -t public
```

3. Visit `http://localhost:8000`.

Alternative (create-project example):

```powershell
composer create-project haryadi/haryadi-framework myapp
```

Note: for the example `create-project` to work you need to publish this package to Packagist. Otherwise, use `git clone` or copy the scaffold.

## Next steps / Improvements you can ask me to add

- Named/parameterized routes, middleware, and route grouping.
- Basic database migrations or simple query builder/ORM.
- More CLI generators (views, routes), and tests.

If you want, I can now add route generators to the CLI and named route support. Tell me which features to add next.

---

Completed: basic scaffold created. See files in the repository root and open `public/index.php` to start.
