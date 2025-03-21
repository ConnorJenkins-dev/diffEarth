<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        // Specify custom pivot table name if needed
        return $this->belongsToMany(User::class, 'role_user')->withTimestamps();
    }
}
