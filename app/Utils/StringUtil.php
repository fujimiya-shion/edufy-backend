<?php
namespace App\Utils;
use App\Models\User;
use Illuminate\Http\Request;
use Str;

class StringUtil
{
    public static function isValidImage($str): bool{
        $allowed = ['jpeg', 'jpg', 'png', 'gif'];
        $ext = pathinfo($str, PATHINFO_EXTENSION);
        return (in_array($ext, $allowed) && str_starts_with($str, 'http')) || str_starts_with($str, 'data:image/');
    }

    public static function getFileExtensionFromBase64($str): string{
        return explode('/', explode(':', substr($str, 0, strpos($str, ';')))[1])[1];
    }

    public static function mapClassToName($class): string{
        $class = explode('\\', $class);
        return strtolower(end($class));
    }

    public static function slug($str): string{
        // Remove accents
        $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        return mb_strtolower(str_replace(' ', '-', $str));
    }

    public static function uuid(): string {
        return (string) Str::uuid();
    }

    public static function genToken(): string {
        $raw = Str::random(60); // random 
        return hash('sha256', $raw);
    }

    public static function getUserToken(Request $request): string|null {
        $authorization = $request->header('User-Authorization');
        if(str_starts_with($authorization, 'Bearer ')) {
            $token = str_replace('Bearer ', '', $authorization);
            return $token;
        }
        return null;
    }
}
