<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isAdmin()) {
            // Regular users trying to access admin routes - redirect to products page
            return redirect()->route('products.index')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}   