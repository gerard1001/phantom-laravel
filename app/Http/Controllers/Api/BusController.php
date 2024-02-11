<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BusController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'plate_number' => 'required|string|max:10|unique:buses',
                'model' => 'required|string|max:20',
                'capacity' => 'required|integer',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ]);
            };

            $bus= [
                'plate_number' => $request->input('plate_number'),
                'model' => $request->input('model'),
                'capacity' => $request->input('capacity'),
            ];

            // $bus->plate_number = $request->input('plate_number');
            // $bus->model = $request->input('model');
            // $bus->capacity = $request->input('capacity');

            // $bus->save();

            $data = Bus::create($bus);

            return response()->json([
                'message' => 'Bus created successfully',
                'status' => Response::HTTP_CREATED,
                'bus' => $data,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Bus creation failed',
                'error' => $error->getMessage(),
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Bus creation failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function all()
    {
        try {
            $buses = Bus::all();
            return response()->json([
                'message' => 'Buses fetched successfully',
                'status' => Response::HTTP_OK,
                'buses' => $buses,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Buses fetch failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function show(string $id)
    {
        try {
            $bus = Bus::with('route', 'user.role')->find($id);
            return response()->json([
                'message' => 'Bus fetched successfully',
                'status' => Response::HTTP_OK,
                'bus' => $bus,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Bus fetch failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function assignRoute(Request $request, string $id)
    {
        try {
            $bus = Bus::find($id);

            $validator = Validator::make($request->all(), [
                'route_id' => 'required|string|exists:routes,id',
            ]);
            
            if($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ]);
            };

            $bus->route()->associate($request->input('route_id'));
            $bus->save();

            return response()->json([
                'message' => 'Route assigned successfully',
                'status' => Response::HTTP_OK,
                'bus' => $bus,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Route assignment failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function assignDriver(Request $request, string $id)
    {
        try {
            $bus = Bus::find($id);
            $userController = new UserController();

            $user = $userController->show($request->input('user_id'));

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|string|exists:users,id',
            ]);
            
            if($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ]);
            };

            if($user->original['user']['role']['name'] && $user->original['user']['role']['name'] !== 'driver') {
                return response()->json([
                    'message' => 'User is not a driver',
                    'status' => Response::HTTP_BAD_REQUEST,
                    'user' => $user->original['user']['role']['name'],
                ]);
            }

            $bus->user()->associate($request->input('user_id'));
            $bus->save();

            return response()->json([
                'message' => 'Driver assigned successfully',
                'status' => Response::HTTP_OK,
                'bus' => $bus,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Driver assignment failed',
                'error' => $error->getMessage(),
            ]);
        };
    }
}
