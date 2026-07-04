<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    use HasFactory;

    protected $table = 'gejala';

    protected $primaryKey = 'id_gejala';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id_gejala',
        'nama_gejala',
    ];

    /**
     * Define relationship between Gejala and Penyakit (Many-to-Many).
     */
    public function penyakit()
    {
        return $this->belongsToMany(
            Penyakit::class,
            'aturan_rule',
            'id_gejala',
            'id_penyakit'
        );
    }
}
