<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Announcement extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'title',
        'content',
        'category',
        'image',
        'file_attachment',
        'is_active',
        'published_at',
        'author_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];


    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getShortContentAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }
}
