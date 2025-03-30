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
}
