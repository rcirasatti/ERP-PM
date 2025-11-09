<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materials';
    protected $fillable = ['supplier_id', 'nama', 'satuan', 'harga'];
    public $timestamps = true;
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }
    
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
