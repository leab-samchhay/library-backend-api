<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role = Role::query();
        $role->when($request->search, function ($q, $search) {
            $q->where('name', 'like', '%'.$search.'%');
        });

        return response()->json([
            'status' => 'success',
            'data' => $role->latest()->paginate($request->per_page ?? 10),
            'message' => 'get data success',
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'integer',
        ]);

        $role = Role::create($validate);

        return response()->json([
            'status' => 'success',
            'data' => $role,
            'message' => 'create success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $role,
            'message' => 'get data success',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'integer',
        ]);

        $role->update($validate);

        return response()->json([
            'status' => 'success',
            'data' => $role,
            'message' => 'update success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'status' => 'success',
            'data' => $role,
            'message' => 'delete success',
        ]);
    }
}
