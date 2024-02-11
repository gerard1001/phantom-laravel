<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{
    public function store(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'origin' => 'required|string|max:40',
                'destination' => 'required|string|max:40',
                'length' => 'required|string|max:40',
            ]);
            
            if($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ]);
            };
            
            $route = [
                'origin' => $request->input('origin'),
                'destination' => $request->input('destination'),
                'length' => $request->input('length'),
            ];
            
            $data = Route::create($route);

            return response()->json([
                'message' => 'Route created successfully',
                'status' => HttpResponse::HTTP_CREATED,
                'route' => $data,
            ]);

        } catch(Exception $error) {
            return response()->json([
                'message' => 'Route creation failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function all()
    {
        try {
            $routes = Route::all();
            return response()->json([
                'message' => 'Routes fetched successfully',
                'status' => HttpResponse::HTTP_OK,
                'routes' => $routes,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Routes fetch failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function show(string $id)
    {
        try {
            $route = Route::with('buses')->find($id);
            return response()->json([
                'message' => 'Route fetched successfully',
                'status' => HttpResponse::HTTP_OK,
                'route' => $route,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Route fetch failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function update(Request $request, $id)
    {
        try {
            $route = Route::find($id);

            $data = [
                'origin' =>  $request->has('origin') ? $request->input('origin') : $route->origin,
                'destination' => $request->has('destination') ? $request->input('destination') : $route->destination,
                'length' =>  $request->has('length') ? $request->input('length') : $route->length,
            ];

            $route->update($data);
            
            return response()->json([
                'message' => 'Route updated successfully',
                'status' => HttpResponse::HTTP_OK,
                'route' => $route,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Route update failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function delete($id)
    {
        try {
            $route = Route::find($id);
            $route->delete();
            return response()->json([
                'message' => 'Route deleted successfully',
                'status' => HttpResponse::HTTP_OK,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Route delete failed',
                'error' => $error->getMessage(),
            ]);
        };
    }

    public function addBuses(Request $request, $id)
    {
        try {
            $busController = new BusController();
            $route = Route::find($id);

            $validator = Validator::make($request->all(), [
                'buses' => 'required|array',
                'buses.*' => 'string|exists:buses,id',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ]);
            };

            $busesIds = $request->input('buses');

            
            foreach ($busesIds as $busId) {
                $bus = $busController->show($busId)->original['bus'];
                
                $route->buses()->save($bus);
            }

            return response()->json([
                'message' => 'Bus added to route successfully',
                'status' => HttpResponse::HTTP_OK,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Route add failed',
                'error' => $error->getMessage(),
            ]);
        }
    }

    public function getBusesInRoute(string $id)
    {
        try {
            $route = Route::with('buses')->find($id);
            return response()->json([
                'message' => 'Buses fetched successfully',
                'status' => HttpResponse::HTTP_OK,
                'buses' => $route->buses,
            ]);
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Buses fetch failed',
                'error' => $error->getMessage(),
            ]);
        };
    }
}
