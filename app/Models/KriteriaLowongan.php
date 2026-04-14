<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KriteriaLowongan extends Model
{
    use SoftDeletes;

    protected $table = 'kriteria_lowongan';
    protected $primaryKey = 'id_kriteria_lowongan';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_lowongan_pekerjaan',
        'nama_kriteria',
        'jenis_kriteria',
        'bobot',
        'nilai_target',
    ];

    protected $dates = ['deleted_at'];

    // ================= RELASI =================

    // ke lowongan pekerjaan
    public function lowongan()
    {
        return $this->belongsTo(LowonganPekerjaan::class, 'id_lowongan_pekerjaan');
    }
}