<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RiwayatDiagnosa extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'riwayat_diagnosa';

    protected $primaryKey = 'id_diagnosa';

    const CREATED_AT = 'tanggal_konsultasi';
    const UPDATED_AT = null;

    protected $fillable = [
        'nama_pasien',
        'gejala_dipilih',
        'hasil_penyakit',
        'solusi',
    ];

    /**
     * Get the attributes that should be cast.
     * Complies with Laravel 11/12 standards.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gejala_dipilih' => 'array',
            'tanggal_konsultasi' => 'datetime',
        ];
    }
}
