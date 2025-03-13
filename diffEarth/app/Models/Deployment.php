<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Deployment extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'deployments';

    protected $fillable = [
        'uid',         // Unique identifier for the deployment
        'name',        // Name of the deployment
        'latitude',    // Latitude coordinate
        'longitude',   // Longitude coordinate
        'description', // Deployment description
        'info',        // Deployment information (shown on layout)
        'layout',      // Deployment layout
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'latitude' => 'float',    // Ensure latitude is stored as a float
        'longitude' => 'float',   // Ensure longitude is stored as a float
        'layout' => 'array',
    ];

    // Validates the deployment data before saving.
    public static function validate(array $data)
    {
        return Validator::make($data, [
            'uid' => ['required', 'string', 'max:255', 'unique:deployments'],
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string', 'max:1000'],
            'info' => ['nullable', 'string'],
            'layout' => ['nullable', 'array'],
        ]);
    }
}
