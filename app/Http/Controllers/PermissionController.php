<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index() {
        return response()->json(\App\Models\Permission::all());
    }

    public function getUserPermissions($userId) {
        $user = \App\Models\User::findOrFail($userId);
        return response()->json(['permissions' => $user->permissions->pluck('id')]);
    }

    public function assignToUser(Request $request, $userId) {
        $user = \App\Models\User::findOrFail($userId);
        $user->permissions()->sync($request->permissions); // array of permission IDs
        return response()->json(['message' => 'Permissions assigned successfully.']);
    }
}
