<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index($collaboratorId)
    {
        $collaborator = Collaborator::find($collaboratorId);
        if (!$collaborator) {
            return response()->json(['message' => 'Collaborator not found'], 404);
        }

        return response()->json([
            'biography' => $collaborator->biography,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'biography' => 'required',
        ]);

        $collaborator = Collaborator::find($request->id);
        $collaborator->biography = $request->biography;
        $collaborator->save();
    }
}
