<?php
namespace Haryadi;

use Haryadi\Http\Request;
use Haryadi\Http\Response;
use Haryadi\Routing\Router;
use Haryadi\Exceptions\Handler;

class Kernel
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function boot(): void
    {
        // start session for CSRF and flash data if not running in CLI
        if (php_sapi_name() !== 'cli' && session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        // load helpers
        $helpers = $this->app->basePath('src/Haryadi/Support/helpers.php');
        if (file_exists($helpers)) {
            require_once $helpers;
        }

        // register error handler
        Handler::register();

        // load app routes
        $routes = $this->app->basePath('routes/web.php');
        if (file_exists($routes)) {
            require $routes;
        }
    }

    public function handle(Request $request): Response
    {
        $response = Router::dispatch($request);
        if (!$response instanceof Response) {
            $resp = new Response((string) $response);
            return $resp;
        }
        return $response;
    }
}
