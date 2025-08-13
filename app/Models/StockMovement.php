<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model {
    protected $fillable = ['variant_id','change_qty','reason','reference','created_by'];
}
