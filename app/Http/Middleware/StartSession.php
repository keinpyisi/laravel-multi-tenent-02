<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession as BaseStartSession;

class StartSession extends BaseStartSession
{
    public function handle($request, Closure $next)
    {
        $response = parent::handle($request, $next);

        // Log session state after handling
        // log_message('Session State', [
        //     'session_id' => $request->session()->getId(),
        //     'session_data' => $request->session()->all()
        // ]);

        return $response;
    }
}