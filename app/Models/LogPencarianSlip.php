<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPencarianSlip extends Model
{
    use HasFactory;

    protected $table = 'logs_pencarian_slip';

    protected $fillable = [
        'user_id',
        'nip',
        'bulan',
        'tahun',
        'unit_kerja',
        'tujuan_unduh',
        'status',
        'response_data',
        'error_message',
        'execution_time_ms',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'bulan' => 'integer',
        'tahun' => 'integer',
        'execution_time_ms' => 'float',
        'response_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get user yang melakukan pencarian
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk pencarian sukses
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope untuk pencarian gagal
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('status', ['failed', 'error']);
    }

    /**
     * Scope untuk filter berdasarkan NIP
     */
    public function scopeByNip($query, string $nip)
    {
        return $query->where('nip', $nip);
    }

    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopeByPeriode($query, int $bulan, int $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    /**
     * Get periode string
     */
    public function getPeriodeStringAttribute(): string
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $namaBulan[$this->bulan] . ' ' . $this->tahun;
    }
}
