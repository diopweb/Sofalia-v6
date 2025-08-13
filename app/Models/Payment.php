<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = ['sale_id','client_transaction_id','client_id','amount','method','created_by'];
}
