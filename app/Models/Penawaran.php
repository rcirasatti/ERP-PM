<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penawaran extends Model
{
    protected $table = 'penawaran';
    protected $fillable = ['no_penawaran', 'client_id', 'tanggal', 'status', 'total_margin', 'total_biaya'];
    protected $casts = [
        'tanggal' => 'date',
        'total_margin' => 'decimal:2',
        'total_biaya' => 'decimal:2',
    ];
    public $timestamps = true;

    /**
     * Get the client that owns the penawaran
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the items for the penawaran
     */
    public function items(): HasMany
    {
        return $this->hasMany(ItemPenawaran::class, 'penawaran_id');
    }

    /**
     * Get the project created from this penawaran (1 penawaran = 1 project)
     */
    public function proyek(): HasOne
    {
        return $this->hasOne(Proyek::class, 'penawaran_id');
    }

    /**
     * Generate unique quotation number
     */
    public static function generateNoPenawaran()
    {
        $year = date('Y');
        $month = date('m');
        $lastQuotation = self::where('no_penawaran', 'LIKE', "PW-{$year}-{$month}-%")
            ->orderBy('no_penawaran', 'DESC')
            ->first();

        $sequence = 1;
        if ($lastQuotation) {
            $parts = explode('-', $lastQuotation->no_penawaran);
            $sequence = intval(end($parts)) + 1;
        }

        return sprintf('PW-%d-%02d-%03d', $year, $month, $sequence);
    }

    /**
     * Get status color for badge
     */
    public function getStatusColor()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'disetujui' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
            'dibatalkan' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'dibatalkan' => 'Dibatalkan',
            default => 'Unknown'
        };
    }
}
