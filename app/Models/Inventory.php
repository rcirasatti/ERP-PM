<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';
    protected $fillable = ['material_id', 'stok'];
    public $timestamps = true;
    
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
