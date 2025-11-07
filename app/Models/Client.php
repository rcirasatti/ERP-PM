<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected $fillable = ['nama', 'kontak', 'email', 'telepon', 'alamat'];
    public $timestamps = true;
}
