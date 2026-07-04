<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    use HasFactory;

    protected $table = 'penyakit';

    protected $primaryKey = 'id_penyakit';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id_penyakit',
        'nama_penyakit',
        'solusi',
    ];

    /**
     * Define relationship between Penyakit and Gejala (Many-to-Many).
     */
    public function gejala()
    {
        return $this->belongsToMany(
            Gejala::class,
            'aturan_rule',
            'id_penyakit',
            'id_gejala'
        );
    }
}
