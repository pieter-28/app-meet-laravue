<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pic extends Model
{
    protected $table = 'pics';

    protected $fillable = [
        'code',
        'name',
        'status',
        'email',
        'phone',
        'archievement',
        'description',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
