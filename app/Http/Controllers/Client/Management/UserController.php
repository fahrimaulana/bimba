<?php

namespace App\Http\Controllers\Client\Management;

use DB, Datatables;
use Illuminate\Http\Request;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Client;
use App\Http\Controllers\Controller;
use App\Models\UserManagement\UserLoginHistory;

class UserController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-user-list');

        $roles = Role::orderBy('display_name', 'asc')->get();

        return view('backend.management.user.index', compact('roles'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-user');

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'username' => 'required|unique:users,username,NULL,id,deleted_at,NULL',
            'password' => 'required|confirmed',
            'role' => 'required|integer|exists:roles,id,platform,' . platform(),
            'active' => 'nullable'
        ]);

        $user = new Client;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->platform = platform();
        $user->client_id = $user->platform == 'Client' ? clientId() : null;
        $user->active = $request->active ? 1 : 0;
        $user->save();

        $user->syncRoles([$request->role]);

        return redirect()->route('client.management.user.index')->with('notif_success', 'New user has been saved successfully!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-user');

        $user = Client::withoutGlobalScope('active')->findOrFail($id);
        $roles = Role::orderBy('display_name', 'asc')->get();

        return view('backend.management.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-user');

        $user = Client::withoutGlobalScope('active')->findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id . ',id,deleted_at,NULL',
            'username' => 'required|unique:users,username,' . $user->id . ',id,deleted_at,NULL',
            'role' => 'required|integer|exists:roles,id,platform,' . platform(),
            'active' => 'nullable'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->active = $request->active ? 1 : 0;
        $user->save();

        $user->syncRoles([$request->role]);

        return redirect()->route('client.management.user.index')->with('notif_success', 'User has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-user');

        $user = Client::withoutGlobalScope('active')->findOrFail($id);

        $user->delete();

        return redirect()->route('client.management.user.index')->with('notif_success', 'User has been deleted successfully!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-user-list');

        $users = Client::withoutGlobalScope('active')
                     ->select(['users.*', 'roles.display_name as role_display_name'])
                     ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                     ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id');

        return Datatables::of($users)
                    ->editColumn('active', function($user) {
                        return $user->active ? '<i class="icon wb-check text-success"></i>' : '<i class="icon wb-close text-danger"></i>';
                    })
                    ->addColumn('action', function($user) {
                        $edit = '<a href="' . route('client.management.user.edit', $user->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit user"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $changePassword = '<a href="' . route('client.management.user.other.change-password', $user->id) . '" class="btn btn-sm btn-icon text-default tl-tip"
                                data-toggle="tooltip" data-original-title="Change Password"><i class="icon fa-key" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.management.user.destroy', $user->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete user"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-user') ? $edit : '') .
                               (userCan('change-user-password') ? $changePassword : '') .
                               (userCan('delete-user') ? $delete : '');
                    })
                    ->rawColumns(["active","action"])
                    ->make(true);
    }

    public function changePassword()
    {
        return view('backend.management.user.change-password');
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        $user = user();

        if (\Hash::check($request->old_password, $user->password) !== true) {
            return validationError('Wrong old password');
        }
        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->back()->with('notif_success', 'Password has been updated successfully!');
    }

    public function changeOtherPassword($id)
    {
        checkPermissionTo('change-user-password');

        return view('backend.management.user.change-password', compact('id'));
    }

    public function updateOtherPassword($id, Request $request)
    {
        checkPermissionTo('change-user-password');

        $this->validate($request, [
            'new_password' => 'required|confirmed'
        ]);

        $user = Client::withoutGlobalScopes(['active'])->findOrFail($id);
        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->back()->with('notif_success', 'Password has been updated successfully!');
    }

    public function showLoginHistory()
    {
        checkPermissionTo('view-user-login-history');

        return view('backend.management.user.login-history');
    }

    public function getLoginHistoryData()
    {
        checkPermissionTo('view-user-login-history');

        $userLoginHistory = UserLoginHistory::with('user')->whereHas('user')->select('user_login_histories.*')
                            ->where(DB::raw('year(user_login_histories.created_at)'), year())
                            ->where(DB::raw('month(user_login_histories.created_at)'), month());

        return Datatables::of($userLoginHistory)
            ->editColumn('created_at', function($userLoginHistory) {
                return $userLoginHistory->created_at->format('d M Y H:i:s');
            })
            ->make(true);
    }
}