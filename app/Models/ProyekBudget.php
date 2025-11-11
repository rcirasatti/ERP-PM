<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProyekBudget extends Model
{
    protected $table = 'proyek_budget';
    
    protected $fillable = [
        'proyek_id',
        'jumlah_rencana',
        'jumlah_realisasi',
    ];

    protected $casts = [
        'jumlah_rencana' => 'decimal:2',
        'jumlah_realisasi' => 'decimal:2',
    ];

    /**
     * Get the project that owns this budget
     */
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    /**
     * Get sisa budget (remaining budget)
     */
    public function getSisaBudgetAttribute()
    {
        return $this->jumlah_rencana - $this->jumlah_realisasi;
    }

    /**
     * Get persentase penggunaan budget
     */
    public function getPersentasePenggunaanAttribute()
    {
        if ($this->jumlah_rencana == 0) {
            return 0;
        }
        return ($this->jumlah_realisasi / $this->jumlah_rencana) * 100;
    }

    /**
     * Get status budget (aman/peringatan/bahaya)
     */
    public function getStatusBudget()
    {
        $persentase = $this->persentase_penggunaan;
        
        if ($persentase < 70) {
            return 'aman';
        } elseif ($persentase < 90) {
            return 'peringatan';
        } else {
            return 'bahaya';
        }
    }

    /**
     * Get status color for badge
     */
    public function getStatusColor()
    {
        $status = $this->getStatusBudget();
        
        return match($status) {
            'aman' => 'bg-green-100 text-green-800',
            'peringatan' => 'bg-yellow-100 text-yellow-800',
            'bahaya' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
