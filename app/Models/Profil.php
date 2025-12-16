<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profil extends Model
{
    protected $table = 'profil';

    protected $fillable = [
        'user_id',
        'nama_depan',
        'nama_belakang',
        'telepon',
    ];

    /**
     * Get the user that owns the profil
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get full name attribute
     */
    public function getNamaLengkapAttribute(): string
    {
        return "{$this->nama_depan} {$this->nama_belakang}";
    }
}
