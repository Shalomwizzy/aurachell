<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StaffInviteMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::whereHas('roles')->with('roles', 'permissions')->latest()->get();
        $roles = Role::whereNotIn('name', ['customer'])->orderBy('name')->get();
        $allPermissions = Permission::orderBy('name')->get();

        return view('admin.staff.index', compact('staff', 'roles', 'allPermissions'));
    }

    public function invite(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,name',
        ]);

        $password = Str::random(12);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
        ]);

        $user->assignRole($data['role']);

        try {
            Mail::to($user->email)->send(new StaffInviteMail($user, $password));
        } catch (\Exception) {
        }

        return back()->with('success', "Staff member invited. Temporary password: {$password}");
    }

    public function changeRole(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $request->validate(['role' => 'required|exists:roles,name']);

        $user->syncRoles([$request->role]);

        return back()->with('success', ucfirst(str_replace('_', ' ', $request->role)).' role assigned to '.$user->name.'.');
    }

    public function toggle(User $user)
    {
        $newValue = ! ($user->is_blocked ?? false);
        $user->update(['is_blocked' => $newValue]);

        return back()->with('success', $newValue ? 'Staff member deactivated.' : 'Staff member activated.');
    }

    public function updatePermissions(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own permissions.');
        }

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user->syncPermissions($request->input('permissions', []));

        return back()->with('success', 'Permissions updated for '.$user->name.'.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();

        return back()->with('success', 'Staff member removed.');
    }
}
