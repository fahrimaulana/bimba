<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserManagement\Admin;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    public function dashboard()
    {
        $users = Admin::with('roles')->oldest()->get();

        return view('admin.dashboard', compact('users'));
    }
}
