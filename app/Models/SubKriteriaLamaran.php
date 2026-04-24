<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKriteriaLamaran extends Model
{
    protected $table = 'sub_kriteria_lamaran';
    protected $primaryKey = 'id_sub_kriteria_lamaran';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_sub_kriteria_lamaran',
        'id_lamaran',
        'id_sub_kriteria',
        'nilai',
    ];

    // ================= RELASI =================

    public function lamaran()
    {
        return $this->belongsTo(
            LamaranPekerjaan::class,
            'id_lamaran',
            'id_lamaran'
        );
    }

    public function subKriteria()
    {
        return $this->belongsTo(
            SubKriteria::class,
            'id_sub_kriteria',
            'id_sub_kriteria'
        );
    }
}
