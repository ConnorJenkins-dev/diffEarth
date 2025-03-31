<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        return response()->json(Alert::all());
    }

    public function destroy($id)
    {
        $alert = Alert::find($id);

        if (!$alert) {
            return response()->json(['message' => 'Alert not found'], 404);
        }

        $alert->delete();

        return response()->json(['message' => 'Alert deleted successfully']);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'location' => 'required|string',
            'column' => 'required|string',
            'threshold' => 'required|numeric',
            'emaillist' => 'required|array',
            'emaillist.*' => 'email',
        ]);

        $alert = Alert::create([
            'location' => $validatedData['location'],
            'column' => $validatedData['column'],
            'threshold' => $validatedData['threshold'],
            'emaillist' => json_encode($validatedData['emaillist']),
        ]);

        return response()->json(['message' => 'Alert created successfully', 'alert' => $alert], 201);
    }
}
