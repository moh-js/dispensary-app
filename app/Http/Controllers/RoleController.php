<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('role-view');

        return view('roles.index', [
            'roles' => Role::withTrashed()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('role-add');

        return view('roles.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('role-add');

        $request->validate([
            'name' => ['required', 'string', 'max:20', 'unique:roles,name'],
        ]);

        Role::create($request->merge(['guard' => 'web'])->except(['_token']));

        flash('Role created successfully');
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $permissions = Permission::query()->get();
        $permissions = $permissions
        ->groupBy(function ($item, $key) {
            return str_before($item['name'], '-', 0);
        })->sortKeys();

        return view('roles.show', ['role' => $role, 'permissions' => $permissions]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $this->authorize('role-update');

        return view('roles.edit', [
            'role' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('role-update');

        $this->validate($request, [
            'name' => ['required', 'string', 'max:20', 'min:3', "unique:roles,name,$role->id,id"],
        ], [
            'unique' => 'This role already exist'
        ]);

        $role->update($request->except(['_token']));

        flash('Role updated successfully');
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if ($role->trashed()) {
            $this->authorize('role-activate');
            $role->restore();
            $action = 'restored';
        } else {
            $this->authorize('role-deactivate');
            $role->delete();
            $action = 'deleted';
        }

        flash("Role $action successfully");
        return redirect()->route('roles.index');
    }

    public function destroyPermanently(Role $role)
    {
        $this->authorize('role-delete');

        $role->delete();

        flash("Role removed permanently successfully");
        return redirect()->route('roles.index');
    }

    public function grantPermission(Request $request, Role $role)
    {
        $this->authorize('role-grant-permission');

        $permissions = $request->except('_token');

        $role->syncPermissions($permissions);

        flash('Permission granted successfully');
        return redirect()->route('roles.index');
    }
}
