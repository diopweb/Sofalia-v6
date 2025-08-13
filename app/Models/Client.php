<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model {
    protected $fillable = ['name','phone','email','address'];
    public function transactions() { return $this->hasMany(ClientTransaction::class); }
    public function sales() { return $this->hasMany(Sale::class); }
}
