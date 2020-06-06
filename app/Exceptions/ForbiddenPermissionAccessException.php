<?php

namespace App\Exceptions;

use App\Models\UserManagement\Permission;

class ForbiddenPermissionAccessException extends \Exception
{
    private $permissionName;
    private $permissionScope;

    public function __construct($permissionCode, $message = 'Validation Error', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $permission = Permission::where('name', $permissionCode)->first();

        $this->permissionName = $permission ? $permission->display_name : $permissionCode;
        $this->permissionScope = $permission ? strtolower(kebab_case($permission->scope)) : 'admin';
    }

    public function getPermissionName()
    {
        return $this->permissionName;
    }

    public function getPermissionScope()
    {
        return $this->permissionScope;
    }
}