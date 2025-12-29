<?php

namespace App\Traits;
use App\Utils\StringUtil;

trait SanctumAuth
{
    
    public function getAccessToken($credentials, $guard = 'admin', $remember = true) {
        if(auth($guard)->attempt($credentials, $remember)) {
            $user = auth($guard)->user();
            return $user->createToken('Personal Access Token')->plainTextToken;
        }
        return null;
    }

    public function getUser(string $class) {
        $class = StringUtil::mapClassToName($class);
        return auth($class)->user();
    }

    public function authenticateUser(array $credentials, string $class, bool $remember = true) {
        $class = StringUtil::mapClassToName($class);
        if(auth($class)->attempt($credentials, $remember)) {
            return auth($class)->user();
        }
        return null;
    }

}

