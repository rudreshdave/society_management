<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Society;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $roles = Role::selectRaw('id, UPPER(name) as name')->pluck('name', 'id');
        $societies = Society::selectRaw('id, UPPER(society_name) as name')->pluck('name', 'id');
        $users = new User();
        if (isset($data['role_id']) && !empty($data['role_id'])) {
            $user_ids = UserRole::where('role_id', $data['role_id'])->pluck('user_id');
        }
        if (isset($data['society_id']) && !empty($data['society_id'])) {
            $user_ids = UserRole::where('society_id', $data['society_id'])->pluck('user_id');
        }
        if (isset($user_ids) && !empty($user_ids)) {
            $users = $users->whereIn('id', $user_ids);
        }
        $users = $users->orderBy('id', 'desc')->get();
        return view('content.admin.roles.list', compact('roles', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
