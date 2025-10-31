<?php

namespace Haryadi\Http\Middleware;

use Haryadi\Application;

class CsrfMiddleware implements Middleware {
    public function handle($request, $next) {
        if ($request->getMethod() === 'POST') {
            $token = $request->getBody()['_csrf'] ?? '';
            if (!Application::getInstance()->getSession()->validateCsrfToken($token)) {
                http_response_code(403);
                die('CSRF token validation failed');
            }
        }
        return $next($request);
    }
}