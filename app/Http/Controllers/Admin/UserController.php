<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Society;
use Illuminate\Http\Request;
use App\Models\User;
// use App\Models\UserRole;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $roles = Role::selectRaw('id, UPPER(name) as name')->whereNotIn('id', [1])->pluck('name', 'id');
        $societies = Society::selectRaw('id, UPPER(society_name) as name')->pluck('name', 'id');

        $users = User::query()

            // ❌ Always exclude Superadmin users
            ->whereDoesntHave('roles', function ($q) {
                $q->where('roles.id', 1);
            })

            // ✅ Filter by Role (only if role_id exists)
            ->when(!empty($request->role_id), function ($q) use ($request) {
                $q->whereHas('roles', function ($qr) use ($request) {
                    $qr->where('roles.id', $request->role_id);
                });
            })

            // ✅ Filter by Society (only if society_id exists)
            ->when($request->filled('society_id'), function ($q) use ($request) {
                $q->whereHas('roles', function ($qr) use ($request) {
                    $qr->where('user_roles.society_id', $request->society_id);
                });
            })


            ->orderBy('id', 'desc')
            ->get();

        return view('content.admin.users.list', compact('roles', 'societies', 'users'));
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

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:users,id',
            'status' => 'required|in:1,2,3'
        ]);

        $user = User::findOrFail($request->id);
        $user->status = $request->status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully'
        ]);
    }
}
