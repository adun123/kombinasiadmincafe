<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'user_id','branch_id','start_cash','end_cash',
        'started_at','ended_at'
    ];

    public function user() { return $this->belongsTo(User::class); }
}

