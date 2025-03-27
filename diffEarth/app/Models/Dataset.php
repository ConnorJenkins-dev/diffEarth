<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasFactory;

    protected $table = 'datasets';
    protected $fillable = [
        'dataset_name',
        'metadata',
        'location_id',
    ];

    public function columns()
    {
        return $this->hasMany(Column::class);
    }

    public function rows()
    {
        return $this->hasMany(Row::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
