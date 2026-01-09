<?php

namespace App\Http\Middleware;

use App\Services\Contracts\Account\IUserService;
use App\Traits\CrudBehaviour;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyUserToken
{
    use CrudBehaviour;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $service = app(IUserService::class);
        $token = $request->header("User-Authorization");

        if (empty($token)) {
            return $this->errorResponse("Unauthenticated", 401);
        }
        
        $user = $service->getBy(['token' => $token])->first;
        
        if(empty($user)) {
            return $this->errorResponse("Unauthenticated", 401);
        }

        return $next($request);
    }
}
