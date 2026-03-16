<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $table = 'places';
    protected $fillable = [
        'code',
        'name',
        'address',
        'description',
        'is_active',
    ];

    public function scopeSearch($query, $search)
    {
        if (blank($search)) {
            return $query;
        }

        return $query
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('address', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%');
    }
}
