<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $fillable = ['proyek_id', 'nama', 'selesai'];

    protected $casts = [
        'deadline' => 'date',
    ];

    /**
     * Get the project this task belongs to
     */
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    /**
     * Get the user this task is assigned to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditugaskan_ke');
    }

    /**
     * Get status color badge for selesai boolean
     */
    public function getStatusColor()
    {
        return $this->selesai 
            ? 'bg-green-100 text-green-800' 
            : 'bg-yellow-100 text-yellow-800';
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabel()
    {
        return $this->selesai ? 'Selesai' : 'Belum Selesai';
    }
}

