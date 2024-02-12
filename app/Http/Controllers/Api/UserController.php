<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $fields = $request->validate([
    'email' => 'required|string',
    'password' => 'required|string'
]);

        $user = User::with('role')->where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'message' => 'Loggin successful',
            'status' => HttpResponse::HTTP_CREATED,
            'token' => $token,
            'role' => $user->role->name,
        ]);

    }

    public function store(Request $request)
    {
        try {
            $user = new User();
            $byName = new RoleController();
            
            $validator = Validator::make($request->all(), [
                'role' => 'required|string|max:40',
                'names' => 'required|string|max:40',
                'email' => 'required|email|max:30|unique:users',
                'birth_date' => 'required|date',
                'phone_number' => 'required|string|max:20|unique:users',
                'password' => 'required|string|min:8|max:20',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
            if($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ]);
            };
            
            $role = $byName->byName($request->input('role'));

            if (isset($role->original['role']['id'])) {
                $id = $role->original['role']['id'];

                $user->role_id = $id;
                $user->names = $request->input('names');
                $user->email = $request->input('email');
                $user->birth_date = $request->input('birth_date');
                $user->phone_number = $request->input('phone_number');
                $user->gender = $request->input('gender');
                $user->password = $request->input('password');

                if($request->hasfile('image')) {
                    $file = $request->file('image');
                    $extenstion = $file->getClientOriginalExtension();
                    $filename = 'http://127.0.0.1:8000/uploads/'.time().'.'.$extenstion;
                    $file->move('uploads/', $filename);
                    $user->image = $filename;
                };

                $user->save();

                return response()->json([
                    'message' => 'User created successfully',
                    'status' => HttpResponse::HTTP_CREATED,
                    'user' => $user,
                ]);

            } else {
                return response()->json([
                    'error' => 'Unexpected response structure',
                    'response' => $role->original,
                ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
            };

        } catch(Exception $error) {
            return response()->json([
                'message' => 'User creation failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function all()
    {
        try {
            $users = User::with('role')->get();

            return response()->json([
                'message' => 'Users fetched successfully',
                'status' => HttpResponse::HTTP_OK,
                'users' => $users,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Users fetch failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function show(string $id)
    {
        try {
            $user = User::with('role')->find($id);
            return response()->json([
                'message' => 'User fetched successfully',
                'status' => HttpResponse::HTTP_OK,
                'user' => $user,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'User fetch failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function search($name)
    {
        try {
            $user = User::where('names', 'like', '%'.$name.'%')->get();

            return response()->json([
                'message' => 'User fetched successfully',
                'status' => HttpResponse::HTTP_OK,
                'user' => $user,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'User seach failed',
                'error' => $error->getMessage(),
            ]);
        };
    }
}
