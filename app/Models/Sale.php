<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model {
    protected $fillable = ['client_id','invoice_number','total','tax','discount','status','created_by'];
    public function client() { return $this->belongsTo(Client::class); }
    public function items() { return $this->hasMany(SaleItem::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
