<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = ['range_id','category_id','name','sku','description'];
    public function category() { return $this->belongsTo(Category::class); }
    public function range() { return $this->belongsTo(Range::class); }
    public function variants() { return $this->hasMany(ProductVariant::class); }
}
