<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Services\RoleService;
use Illuminate\Support\Str;
use DB;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::orderBy('id', 'desc')->get();
        return view('content.admin.roles.list', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name'], '');
        $this->roleService->saveRole($validated);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, $id)
    {
        $validated = $request->validated();
        $validated['id'] = $id;
        $validated['slug'] = Str::slug($validated['name'], '');
        $this->roleService->saveRole($validated, $id);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Role::findOrFail($id)->delete();


        return back()->with('success', 'Role deleted successfully');
    }
}
