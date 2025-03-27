<?php

namespace App\Http\Controllers;

use App\Models\Deployment;
use Illuminate\Http\Request;

class DeploymentController extends Controller
{
    public function index()
    {
        return response()->json(Deployment::all());
    }

    public function store(Request $request)
    {
        try {
            // Validate incoming data
            $validated = $request->validate([
                'uid' => 'required|unique:deployments,uid|string|max:255', // Ensure UID is unique and valid
                'name' => 'required|string|max:255',
                'latitude' => 'required|numeric|min:-90|max:90', // Latitude must be within valid range
                'longitude' => 'required|numeric|min:-180|max:180', // Longitude must be within valid range
                'description' => 'nullable|string', // Description is optional
            ]);

            // Create the deployment record
            $deployment = Deployment::create($validated);

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
}
