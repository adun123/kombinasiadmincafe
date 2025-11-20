<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id','branch_id','name','sku','price',
        'cost_price','has_variant','stock','image'
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function variants() { return $this->hasMany(ProductVariant::class); }
    public function stockLogs() { return $this->hasMany(StockLog::class); }
}
