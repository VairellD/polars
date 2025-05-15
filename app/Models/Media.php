<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'file_url',
        'file_type',
        'file_extension',
        'file_size',
    ];

    /**
     * Get the post that owns the media.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}