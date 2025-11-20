<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice','user_id','table_id','branch_id','type',
        'subtotal','tax','service_charge','discount','total',
        'payment_method','paid_amount','change_amount','status','synced'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function table() { return $this->belongsTo(Table::class); }
    public function items() { return $this->hasMany(TransactionItem::class); }
}
