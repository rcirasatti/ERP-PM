<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    protected $table = 'inventories';
    protected $fillable = ['material_id', 'stok'];
    public $timestamps = true;
    
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Get the logs for this inventory
     */
    public function logs(): HasMany
    {
        return $this->hasMany(LogInventory::class, 'material_id', 'material_id');
    }
}
