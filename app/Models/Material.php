<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materials';
    protected $fillable = ['kode', 'supplier_id', 'nama', 'satuan', 'harga', 'type', 'track_inventory'];
    public $timestamps = true;
    
    // Enum untuk tipe material
    public const TYPE_BARANG = 'BARANG';
    public const TYPE_JASA = 'JASA';
    public const TYPE_TOL = 'TOL';
    public const TYPE_LAINNYA = 'LAINNYA';
    
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
    
    /**
     * Check if material needs inventory tracking
     */
    public function needsInventoryTracking(): bool
    {
        return $this->track_inventory && $this->type === self::TYPE_BARANG;
    }
    
    /**
     * Get all material types
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_BARANG => 'Barang',
            self::TYPE_JASA => 'Jasa',
            self::TYPE_TOL => 'Tol',
            self::TYPE_LAINNYA => 'Lainnya',
        ];
    }
    
    /**
     * Check if material requires supplier
     */
    public function requiresSupplier(): bool
    {
        return $this->type === self::TYPE_BARANG;
    }
}
