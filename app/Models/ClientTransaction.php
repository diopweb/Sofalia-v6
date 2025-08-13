<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTransaction extends Model {
    protected $fillable = ['client_id','type','amount','reference','note','created_by'];
    public function client() { return $this->belongsTo(Client::class); }
}
