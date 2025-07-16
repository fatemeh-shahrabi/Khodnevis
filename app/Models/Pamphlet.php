<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pamphlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'html_content',
        'title',
        'audio_path',
    ];

    /**
     * Get the user that owns the pamphlet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}