<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json(Permission::all());
    }

    public function getUserPermissions($userId)
    {
        $user = User::findOrFail($userId);

        return response()->json(['permissions' => $user->permissions->pluck('id')]);
    }

    public function assignToUser(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->permissions()->sync($request->permissions); // array of permission IDs

        return response()->json(['message' => 'Permissions assigned successfully.']);
    }
}
