<?php

namespace App\Models\UserManagement;

use App\Models\UserManagement\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserLoginHistory extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
