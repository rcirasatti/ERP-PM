<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogInventory extends Model
{
    protected $table = 'log_inventory';
    protected $fillable = ['material_id', 'jenis', 'jumlah', 'tanggal', 'proyek_id', 'catatan', 'dibuat_oleh'];
    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];
    public $timestamps = false;

    /**
     * Get the material that owns the log
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Get the user that created the log
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
