<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';
    
    protected $fillable = [
        'proyek_id',
        'tanggal',
        'kategori',
        'deskripsi',
        'jumlah',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    /**
     * Get the project that owns this expense
     */
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    /**
     * Get the user who created this expense
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Get kategori label in Indonesian
     */
    public function getKategoriLabel()
    {
        return match($this->kategori) {
            'material' => 'Material',
            'gaji' => 'Gaji',
            'bahan_bakar' => 'Bahan Bakar',
            'peralatan' => 'Peralatan',
            'lainnya' => 'Lainnya',
            default => 'Unknown'
        };
    }

    /**
     * Get kategori color for badge
     */
    public function getKategoriColor()
    {
        return match($this->kategori) {
            'material' => 'bg-blue-100 text-blue-800',
            'gaji' => 'bg-green-100 text-green-800',
            'bahan_bakar' => 'bg-yellow-100 text-yellow-800',
            'peralatan' => 'bg-purple-100 text-purple-800',
            'lainnya' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
