<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCsvJob;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function store(Request $request)
    {
        // File is required and must be a CSV
        $request->validate([
            'file' => 'required|file|mimes:csv|max:20480'
        ], [
            'file.mimes' => 'Uploaded file must be a valid CSV.'
        ]);

        $file = $request->file('file');
        $destinationPath = public_path('uploads');
        $timestampedFilename = now()->format('YmdHis') . '_' . $file->getClientOriginalName();
        $file->move($destinationPath, $timestampedFilename);
        $relativePath = 'uploads/' . $timestampedFilename;
        $locationId = $request->input('location');

        ProcessCsvJob::dispatch($relativePath, $timestampedFilename, $locationId);

        return response()->json([
            'message' => 'File uploaded successfully and is being processed in the background.'
            . ' Check back in a few minutes.'
        ]);
    }
}
