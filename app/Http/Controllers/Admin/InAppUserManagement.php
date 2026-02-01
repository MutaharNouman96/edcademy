<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InAppUserManagement extends Controller
{
    /**
     * Internal app users (admin/staff).
     */
    public function usersIndex(Request $request)
    {
        $query = User::query()
            ->where('role', 'admin')
            ->with(['roles', 'permissions']);

        if ($request->filled('q')) {
            $search = trim((string) $request->q);
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%'])
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->latest()->paginate(15);
        $users->appends($request->query());

        return view('admin.inapp_users.index', compact('users'));
    }

    public function usersCreate()
    {
        $roles = Role::query()->orderBy('name')->get();
        $permissions = Permission::query()->orderBy('name')->get();

        return view('admin.inapp_users.create', compact('roles', 'permissions'));
    }

    public function usersStore(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            // Legacy role column (kept for backwards compatibility with existing middleware).
            'role' => 'admin',
        ]);

        $user->syncRoles($validated['roles'] ?? []);
        $user->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.inapp-users.index')->with('success', 'Internal user created.');
    }

    public function usersEdit(User $user)
    {
        abort_unless($user->role === 'admin', 404);

        $roles = Role::query()->orderBy('name')->get();
        $permissions = Permission::query()->orderBy('name')->get();

        $userRoleNames = $user->roles->pluck('name')->all();
        $userPermissionNames = $user->permissions->pluck('name')->all();

        return view('admin.inapp_users.edit', compact('user', 'roles', 'permissions', 'userRoleNames', 'userPermissionNames'));
    }

    public function usersUpdate(Request $request, User $user)
    {
        abort_unless($user->role === 'admin', 404);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'role' => 'admin', // keep internal users as admin in legacy column
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles($validated['roles'] ?? []);
        $user->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.inapp-users.index')->with('success', 'Internal user updated.');
    }

    public function usersDestroy(User $user)
    {
        abort_unless($user->role === 'admin', 404);

        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'Internal user deleted.');
    }

    /**
     * Roles & permissions.
     */
    public function accessControlIndex()
    {
        $roles = Role::query()->with('permissions')->orderBy('name')->get();
        $permissions = Permission::query()->orderBy('name')->get();

        return view('admin.access_control.index', compact('roles', 'permissions'));
    }

    public function roleStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return back()->with('success', 'Role created.');
    }

    public function roleUpdate(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return back()->with('success', 'Role updated.');
    }

    public function roleDestroy(Role $role)
    {
        if ($role->name === 'admin') {
            return back()->with('error', 'The admin role cannot be deleted.');
        }

        $role->delete();

        return back()->with('success', 'Role deleted.');
    }

    public function permissionStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
        ]);

        Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        return back()->with('success', 'Permission created.');
    }

    public function permissionDestroy(Permission $permission)
    {
        $permission->delete();

        return back()->with('success', 'Permission deleted.');
    }
}

