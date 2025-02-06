<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;

    protected $table = 'columns';

    protected $fillable = [
        'dataset_id',
        'column_name',
        'metadata',
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
