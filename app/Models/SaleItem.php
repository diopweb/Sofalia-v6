<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model {
    protected $fillable = ['sale_id','variant_id','qty','unit_price','total'];
    public function variant() { return $this->belongsTo(ProductVariant::class); }
}
