<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'address'];

    public function users() { return $this->hasMany(User::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }
    public function tables() { return $this->hasMany(Table::class); }
}
