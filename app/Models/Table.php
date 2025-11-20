<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['name', 'branch_id'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
