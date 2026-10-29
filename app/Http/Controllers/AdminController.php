<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user.view', only: ['index']),
            new Middleware('permission:user.create', only: ['create', 'store']),
            new Middleware('permission:user.edit', only: ['edit', 'update']),
            new Middleware('permission:user.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $users = User::query()
            ->with('roles')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function create(): View
    {
        return view('admin.users.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['nullable', 'array'],
            'roles.*' => [Rule::exists('roles', 'name')->where('guard_name', 'web')],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [Rule::exists('permissions', 'name')->where('guard_name', 'web')],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // di-hash otomatis oleh cast
            'email_verified_at' => now(),
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        $user->syncRoles($this->filterRoles($data['roles'] ?? []));
        $user->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} berhasil dibuat.");
    }

    public function edit(User $user): View
    {
        $this->protectSuperAdmin($user);

        return view('admin.users.edit', array_merge($this->formData(), compact('user')));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->protectSuperAdmin($user);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'roles' => ['nullable', 'array'],
            'roles.*' => [Rule::exists('roles', 'name')->where('guard_name', 'web')],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [Rule::exists('permissions', 'name')->where('guard_name', 'web')],
        ]);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'updated_by' => $request->user()->id,
        ];

        if (! empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        $user->update($payload);

        $roles = $this->filterRoles($data['roles'] ?? []);

        // Super Admin tidak boleh mencabut role Super Admin miliknya sendiri
        if ($user->is($request->user()) && $user->hasRole('Super Admin')) {
            $roles[] = 'Super Admin';
        }

        $user->syncRoles(array_unique($roles));
        $user->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} berhasil diperbarui.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        $this->protectSuperAdmin($user);

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User {$name} berhasil dihapus.");
    }

    /**
     * Data untuk form create/edit.
     */
    private function formData(): array
    {
        $roles = Role::query()
            ->where('guard_name', 'web')
            ->when(! auth()->user()->hasRole('Super Admin'), fn ($q) => $q->where('name', '!=', 'Super Admin'))
            ->orderBy('name')
            ->get();

        $permissionGroups = Permission::query()
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get()
            ->groupBy(fn ($p) => Str::contains($p->name, '.') ? Str::before($p->name, '.') : 'lainnya');

        return compact('roles', 'permissionGroups');
    }

    private function filterRoles(array $roles): array
    {
        if (! auth()->user()->hasRole('Super Admin')) {
            return array_values(array_diff($roles, ['Super Admin']));
        }

        return array_values($roles);
    }


    private function protectSuperAdmin(User $target): void
    {
        abort_if(
            $target->hasRole('Super Admin') && ! auth()->user()->hasRole('Super Admin'),
            403,
            'Hanya Super Admin yang boleh mengelola akun Super Admin.'
        );
    }
}
