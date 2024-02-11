<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Exception;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function all() {
        try {
            $users = Role::all();
            return response()->json([
                'message' => 'Roles fetched successfully',
                'status' => Response::HTTP_OK,
                'users' => $users,
            ]);
        }
        catch(Exception $error) {
            return response()->json([
                'message' => 'Roles fetch failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function show($id) {
        try {
            $role = Role::find($id);
            return response()->json([
                'message' => 'Role fetched successfully',
                'status' => Response::HTTP_OK,
                'role' => $role,
            ]);
        }
        catch(Exception $error) {
            return response()->json([
                'message' => 'Role fetch failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function byName($name) {
        try {
            $role = Role::where('name', $name)->first();
            
            return response()->json([
                'message' => 'Role fetched successfully',
                'status' => Response::HTTP_OK,
                'role' => $role,
            ]);
            // return $role;
        }
        catch(Exception $error) {
            return response()->json([
                'message' => 'Role fetch failed',
                'error' => $error->getMessage(),
            ]);
        };
    }
}
