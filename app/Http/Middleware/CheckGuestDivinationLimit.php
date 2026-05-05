<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGuestDivinationLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            return $next($request);
        }

        if ($request->session()->get('has_performed_divination', false)) {
            return to_route('home')->with('warning', __('You can only perform one divination as a guest. Please register to continue.'));
        }

        return $next($request);
    }
}
