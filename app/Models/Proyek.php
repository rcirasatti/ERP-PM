<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyek extends Model
{
    protected $table = 'proyek';
    protected $fillable = ['nama', 'deskripsi', 'client_id', 'penawaran_id', 'lokasi', 'tanggal_mulai', 'tanggal_selesai', 'status', 'persentase_progres'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'persentase_progres' => 'float',
    ];

    /**
     * Get the penawaran that created this project
     */
    public function penawaran(): BelongsTo
    {
        return $this->belongsTo(Penawaran::class, 'penawaran_id');
    }

    /**
     * Get the client that owns the project
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get all tasks for this project
     */
    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'proyek_id');
    }

    /**
     * Calculate project progress from tasks
     */
    public function hitungProgress()
    {
        $tasks = $this->tugas()->get();
        
        if ($tasks->isEmpty()) {
            $this->persentase_progres = 0;
            $this->save();
            return 0;
        }

        // Count tasks completed (selesai = true)
        $completedCount = $tasks->where('selesai', true)->count();
        $progress = ($completedCount / $tasks->count()) * 100;
        
        $this->persentase_progres = (float) round($progress, 2);
        $this->save();
        
        return $progress;
    }

    /**
     * Get status color for badge
     */
    public function getStatusColor()
    {
        return match($this->status) {
            'baru' => 'bg-blue-100 text-blue-800',
            'survei' => 'bg-indigo-100 text-indigo-800',
            'instalasi' => 'bg-yellow-100 text-yellow-800',
            'pengujian' => 'bg-orange-100 text-orange-800',
            'selesai' => 'bg-green-100 text-green-800',
            'bast' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'baru' => 'Baru',
            'survei' => 'Survei',
            'instalasi' => 'Instalasi',
            'pengujian' => 'Pengujian',
            'selesai' => 'Selesai',
            'bast' => 'BAST',
            default => 'Unknown'
        };
    }

    /**
     * Get progress bar color based on percentage
     */
    public function getProgressColor()
    {
        $progress = $this->persentase_progres;
        
        if ($progress < 25) {
            return 'bg-red-500';
        } elseif ($progress < 50) {
            return 'bg-orange-500';
        } elseif ($progress < 75) {
            return 'bg-yellow-500';
        } else {
            return 'bg-green-500';
        }
    }
}
