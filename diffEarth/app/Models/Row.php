<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Row extends Model
{
    use HasFactory;

    protected $table = 'rows';
    protected $fillable = [
        'dataset_id',
        'timestamp',
    ];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class);
    }

    public function datapoints()
    {
        return $this->hasMany(Datapoint::class);
    }
}
