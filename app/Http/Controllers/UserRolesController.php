<?php

namespace App\Http\Controllers;

use App\Models\UserRoles;
use Illuminate\Http\Request;

class UserRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_role = UserRoles::with('user','role')->get();

        return response()->json([
            'status' => 'success',
            'data' => $user_role,
            'message' => 'get data success'
        ]);
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
        $validate = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        $user_role = UserRoles::create($validate);

        return response()->json([
            'status' => 'success',
            'data' => $user_role,
            'message' => 'creat success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserRoles $userRoles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserRoles $userRoles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user_role = UserRoles::findOrFail($id);
        $validate = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        $user_role->update($validate);

        return response()->json([
            'status' => 'success',
            'data' => $user_role,
            'message' => 'update success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user_role = UserRoles::findOrFail($id);
        $user_role->delete();

        return response()->json([
            'status' => 'success',
            'data' => $user_role,
            'message' => 'delete success'
        ]);
    }
}
