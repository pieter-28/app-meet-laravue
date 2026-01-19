<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeMeeting extends Model
{
    protected $fillable = ['type_meeting_code', 'type_meeting_name', 'type_meeting_description'];

    public function scopeSearch($query, $search)
    {
        if (blank($search)) {
            return $query;
        }

        return $query
            ->where('type_meeting_name', 'like', '%' . $search . '%')
            ->orWhere('type_meeting_code', 'like', '%' . $search . '%')
            ->orWhere('type_meeting_description', 'like', '%' . $search . '%');
    }
}
