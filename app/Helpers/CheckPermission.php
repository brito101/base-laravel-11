<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class CheckPermission
{

    public static function checkAuth(string $auth): void
    {
        if (self::roleExist($auth) && !Auth::user()->hasPermissionTo($auth)) {
            abort(403, 'Acesso não autorizado');
        }
    }

    public static function checkManyAuth(array $auth): void
    {
        if (self::roleExist($auth) && !Auth::user()->hasAnyPermission($auth)) {
            abort(403, 'Acesso não autorizado');
        }
    }

    private static function roleExist($name)
    {
        if (\count(Permission::where('name', $name)->get()) > 0) {
            return true;
        } else {
            if (is_array($name)) {
                foreach ($name as $item) {
                    Permission::create(['name' => $item]);
                }
            } else {
                Permission::create(['name' => $name]);
            }
            return true;
        }
    }
}
