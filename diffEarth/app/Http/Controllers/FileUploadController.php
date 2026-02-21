<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCsvJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
//         $destinationPath = public_path('uploads');
//         $timestampedFilename = now()->format('YmdHis') . '_' . $file->getClientOriginalName();
//         $file->move($destinationPath, $timestampedFilename);
//         $relativePath = 'uploads/' . $timestampedFilename;

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeName = Str::slug($originalName); // remove spaces & weird chars

        $timestampedFilename = now()->format('YmdHis') . '_' . $safeName . '.csv';

        $path = $file->storeAs('uploads', $timestampedFilename);

        ProcessCsvJob::dispatch($path, $timestampedFilename);

        Log::info('Dispatching CSV job', [
            'path' => $path,
            'filename' => $timestampedFilename
        ]);

        //ProcessCsvJob::dispatch($relativePath, $timestampedFilename);
        //ProcessCsvJob::dispatch($path, $timestampedFilename);

        return response()->json([
            'message' => 'File uploaded successfully and is being processed.'
            . ' Please do not leave this page until it appears in the table.'
        ]);
    }
}
