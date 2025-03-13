<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeploymentInProgress extends Model
{
    use HasFactory;

    protected $table = 'deployments_in_progress';
    protected $fillable = ['id', 'name', 'info', 'layout'];
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $casts = [
        'layout' => 'array',
    ];
}
