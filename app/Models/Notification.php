<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'history_id',
        'message',
        'reply'
        
    ];

    /**
     * Get the employee that owns the comment.
     */
    public function history()
    {
        return $this->belongsTo(History::class);
    }
}
