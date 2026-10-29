<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:permission.view', only: ['index']),
            new Middleware('permission:permission.create', only: ['store']),
            new Middleware('permission:permission.edit', only: ['update']),
            new Middleware('permission:permission.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->withCount('roles')
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.permissions.index', compact('permissions', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                'regex:/^[a-z0-9]+([._-][a-z0-9]+)*$/',
                Rule::unique('permissions', 'name')->where('guard_name', 'web'),
            ],
        ], [
            'name.regex' => 'Gunakan huruf kecil, angka, dan pemisah titik/strip/underscore. Contoh: product.view',
            'name.unique' => 'Permission dengan nama ini sudah ada.',
        ]);

        $permission = Permission::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission {$permission->name} berhasil dibuat.");
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                'regex:/^[a-z0-9]+([._-][a-z0-9]+)*$/',
                Rule::unique('permissions', 'name')
                    ->where('guard_name', 'web')
                    ->ignore($permission->id),
            ],
        ], [
            'name.regex' => 'Gunakan huruf kecil, angka, dan pemisah titik/strip/underscore. Contoh: product.view',
            'name.unique' => 'Permission dengan nama ini sudah ada.',
        ]);

        $permission->update(['name' => $data['name']]);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission {$permission->name} berhasil diperbarui.");
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $name = $permission->name;

        $permission->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission {$name} berhasil dihapus.");
    }
}
