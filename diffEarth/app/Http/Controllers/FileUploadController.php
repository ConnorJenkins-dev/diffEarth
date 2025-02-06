<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    public function store(Request $request)
    {
        Log::info('File uploaded');
        $data = $request;

        $file = $data->file('file');
        // Get the original file name or create your own
        $filename = $file->getClientOriginalName();

        // Move the file to a destination within the public directory
        $destinationPath = public_path('uploads');
        $file->move($destinationPath, $filename);

        return response()->json(['message' => 'File uploaded successfully']);
    }
}
