<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Models\Datapoint;
use App\Models\Dataset;
use Illuminate\Support\Facades\Log;

class GraphDataController extends Controller
{
    // return datasets, this is a list of all uploaded CSV files
    public function getAllDatasets()
    {
        try {
            $datasets = Dataset::all();
            Log::info('Datasets retrieved');
            return response()->json($datasets);
        } catch (\Exception $e) {
            Log::error('Error retrieving datasets: ' . $e->getMessage());
            return response()->json(['message' => 'Error retrieving datasets'], 500);
        }
    }
    // return specific columns by datasetID, this is a list of all the types of data stored from the dataset
    public function getAllColumns($datasetId)
    {
        try {
            $dataset = Dataset::find($datasetId);
            $columns = $dataset->columns;
            return response()->json($columns);
        } catch (\Exception $e) {
            Log::error('Error retrieving columns: ' . $e->getMessage());
            return response()->json(['message' => 'Error retrieving columns'], 500);
        }
    }
    // return datapoint by columnID, these will be y co-ordinates from the dataset
    public function getDataPoints($columnId)
    {
        try {
            $column = Column::find($columnId);
            $dataPoints = $column->dataPoints;
            return response()->json($dataPoints);
        } catch (\Exception $e) {
            Log::error('Error retrieving datapoints: ' . $e->getMessage());
            return response()->json(['message' => 'Error retrieving datapoints'], 500);
        }
    }
    // return the timestamp for the dataset, this is identified by the 'rowID' in the datapoints table
    public function getTimestamp($rowId)
    {
        try {
            $row = Row::find($rowId);
            $timestamp = $row->timestamp;
            return response()->json($timestamp);
        } catch (\Exception $e) {
            Log::error('Error retrieving timestamp: ' . $e->getMessage());
            return response()->json(['message' => 'Error retrieving timestamp'], 500);
        }
    }


    // return datapoint by columnId and timestamp by rowId
    public function getDataAndTimestamp($columnId)
    {
        try {
            $pointAndTimeStamp = Datapoint::where('column_id', $columnId)
                ->join('rows', 'rows.id', '=', 'datapoints.row_id')
                ->with('row')
                ->orderBy('rows.timestamp', 'asc')
                ->get()
                ->map(function ($datapoint) {
                    return [
                        'data' => $datapoint->data,
                        'timestamp' => $datapoint->row->timestamp,
                    ];
                });
            return response()->json($pointAndTimeStamp);
        } catch (\Exception $e) {
            Log::error('Error retrieving datapoints and timestamps: ' . $e->getMessage());
            return response()->json(['message' => 'Error retrieving datapoints and timestamps'], 500);
        }
    }
}
