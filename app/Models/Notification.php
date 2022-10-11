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

    public static function isLastResponseAdded($history_id){
        return ! Notification::where('history_id','=',$history_id)->whereIsNull('reply')->exists();
    }

    public static function getLastResponseTime($history_id){
        $record= Notification::where('history_id','=',$history_id)->whereNotNull('reply')->latest()->first()->updated_at;
        return $record;
    }
}
