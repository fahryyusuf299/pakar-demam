<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AturanRule extends Model
{
    protected $table = 'aturan_rule';

    protected $primaryKey = 'id_rule';

    protected $fillable = [
        'id_penyakit',
        'id_gejala',
    ];

    /**
     * Relationship to Penyakit
     */
    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class, 'id_penyakit', 'id_penyakit');
    }

    /**
     * Relationship to Gejala
     */
    public function gejala()
    {
        return $this->belongsTo(Gejala::class, 'id_gejala', 'id_gejala');
    }
}
