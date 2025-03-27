<?php

namespace App\Http\Controllers;

use App\Models\Deployment;
use App\Models\DeploymentInProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DeploymentController extends Controller
{
    public function index()
    {
        return response()->json(Deployment::all());
    }


    public function getLatLongForDeployment($uuid)
    {
        Log::info('UUID received: ' . $uuid);

        $deployment = Deployment::where('uid', $uuid)->first();

        if (!$deployment) {
            return response()->json(['message' => 'Deployment not found'], 404);
        }

        // Assuming latitude and longitude are stored as 'lat' and 'long' in the deployment model
        // Assuming the columns in the database are named latitude and longitude
        $latLong = [
            'lat' => $deployment->latitude, // Change 'lat' to 'latitude'
            'long' => $deployment->longitude, // Change 'long' to 'longitude'
        ];

        Log::info($latLong);
        return response()->json($latLong);
    }
    public function store(Request $request)
    {
        try {
            Log::info('request: ' . json_encode($request->all()));
            // Validate incoming data
            $validated = $request->validate([
                'uid' => 'nullable|string|max:255|unique:deployments',
                'name' => 'required|string|max:255',
                'latitude' => 'required|numeric|min:-90|max:90',
                'longitude' => 'required|numeric|min:-180|max:180',
                'description' => 'nullable|string',
                'info' => 'nullable|string',
                'layout' => 'nullable|array',
            ]);

            // Create the deployment record
            $deployment = Deployment::create([
                'uid' => $validated['uid'] ?? Str::uuid(),
                'name' => $validated['name'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'description' => $validated['description'] ?? null,
                'info' => $validated['info'] ?? null,
                'layout' => $validated['layout'] ?? null,
            ]);

            // Reset deployment in progress
            DeploymentInProgress::truncate();

            DeploymentInProgress::create([
                'id' => 1,
                'name' => 'Untitled Dashboard',
                'info' => '',
                'layout' => json_encode([
                    [
                        'x' => 0,
                        'y' => 0,
                        'w' => 2,
                        'h' => 3,
                        'i' => 'graph_0',
                        'itemData' => [
                            [
                                'columnId' => null,
                                'traceName' => '',
                            ]
                        ]
                    ],
                    [
                        'x' => 2,
                        'y' => 0,
                        'w' => 2,
                        'h' => 3,
                        'i' => 'table_1',
                        'itemData' => [
                            [
                                'datasetIds' => [],
                            ]
                        ]
                    ],
                ]),
            ]);

            // Return the newly created deployment with a 201 status code
            return response()->json([
                'message' => 'Deployment added successfully.',
                'data' => $deployment,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->validator->errors(),
            ], 422); // Use HTTP status code 422 (Unprocessable Entity)
        } catch (\Exception $e) {
            // Handle other exceptions (e.g., database errors)
            return response()->json([
                'message' => 'Failed to add deployment.',
                'error' => $e->getMessage(),
            ], 500); // Use HTTP status code 500 (Internal Server Error)
        }
    }

    public function show($id)
    {
        return response()->json(Deployment::findOrFail($id));
    }

    public function inProgressIndex()
    {
        $deployment = DeploymentInProgress::all()->first();
        if (!$deployment) {
            return response()->json([], 404);
        }
        $deployment->layout = json_decode($deployment->layout, true);
        Log::info('Deployment: ', [$deployment]);
        return response()->json($deployment);
    }

    public function inProgressStore(Request $request)
    {
        $request->validate([
            'layout' => 'required|array',
        ]);

        DeploymentInProgress::updateOrCreate(
            ['id' => $request->id],
            [
                'id' => $request->id ?? 1,
                'name' => $request->name ?? 'Untitled Dashboard',
                'layout' => json_encode($request->layout),
                'info' => $request->info,
            ],
        );

        return response()->json(['message' => 'Layout saved successfully!']);
    }

    public function showByUuid($uuid)
    {
        $deployment = Deployment::where('uid', $uuid)->firstOrFail();

        return response()->json([
            'layout' => $deployment->layout,
            'info' => $deployment->info,
            'description' => $deployment->description,
            'name' => $deployment->name,
        ]);
    }

    public function destroy($id)
    {
        $deployment = Deployment::findOrFail($id);
        $deployment->delete();

        return response()->json(['message' => 'Deployment deleted.']);
    }
}
