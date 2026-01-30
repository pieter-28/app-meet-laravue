<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        'topic_code',
        'topic_name',
        'topic_description',
    ];

    public function scopeSearch($query, $search)
    {
        if (blank($search)) {
            return $query;
        }

        return $query
            ->where('topic_name', 'like', '%' . $search . '%')
            ->orWhere('topic_code', 'like', '%' . $search . '%')
            ->orWhere('topic_description', 'like', '%' . $search . '%');
    }
}
