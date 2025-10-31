<?php

namespace Haryadi\Http\Middleware;

interface Middleware {
    public function handle($request, $next);
}