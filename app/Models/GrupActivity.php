<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupActivity extends Model
{
    protected $table = "grup_activities";
    protected $fillable = [
        'code',
        'name',
        'slug',
        'status',
        'description',
    ];
}
