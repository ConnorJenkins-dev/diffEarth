<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    public function index(Request $request)
    {
        // Fetch datasets and paginate the results
        $datasets = Dataset::query()
            ->orderBy('id', 'desc')
            ->paginate(50);

        // Return the datasets as json response
        return response()->json($datasets);
    }
}
