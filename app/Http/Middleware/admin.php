<?php

namespace CamaleonERP\Http\Middleware;

use Closure;

class admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if ($request->user()->nivel == 'Invitado') {
        abort(403, "No tienes las credenciales para acceder a esta función.");
      }
        return $next($request);
    }
}
