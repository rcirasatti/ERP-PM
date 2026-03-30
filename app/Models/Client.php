<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'clients';
    protected $fillable = ['nama', 'kontak', 'email', 'telepon', 'alamat'];
    public $timestamps = true;

    /**
     * Get all penawaran (quotations) for this client
     */
    public function penawaran(): HasMany
    {
        return $this->hasMany(Penawaran::class, 'client_id');
    }
}
