<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentShare extends Model
{
    protected $table = 'shares';
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'content_type',
        'content',
        'file_url',
        'short_code'
    ];
}
