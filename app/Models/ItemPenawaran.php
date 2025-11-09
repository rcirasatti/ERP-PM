<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPenawaran extends Model
{
    protected $table = 'item_penawaran';
    protected $fillable = ['penawaran_id', 'material_id', 'jumlah', 'harga_asli', 'persentase_margin', 'harga_jual'];
    protected $casts = [
        'jumlah' => 'integer',
        'harga_asli' => 'decimal:2',
        'persentase_margin' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];
    public $timestamps = true;

    /**
     * Get the penawaran that owns the item
     */
    public function penawaran(): BelongsTo
    {
        return $this->belongsTo(Penawaran::class, 'penawaran_id');
    }

    /**
     * Get the material for this item
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    /**
     * Calculate total cost (harga jual * jumlah)
     */
    public function getTotalCostAttribute()
    {
        return $this->harga_jual * $this->jumlah;
    }

    /**
     * Calculate total biaya asli (harga asli * jumlah)
     */
    public function getTotalBiayaAsliAttribute()
    {
        return $this->harga_asli * $this->jumlah;
    }

    /**
     * Calculate margin value (total harga jual - total biaya asli)
     */
    public function getMarginValueAttribute()
    {
        return $this->getTotalCostAttribute() - $this->getTotalBiayaAsliAttribute();
    }
}
