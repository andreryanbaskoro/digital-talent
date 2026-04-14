<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPerhitungan extends Model
{
    use SoftDeletes;

    protected $table = 'detail_perhitungan';
    protected $primaryKey = 'id_detail_perhitungan';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_hasil_perhitungan',
        'nama_kriteria',
        'nilai_pelamar',
        'nilai_target',
        'selisih',
        'bobot_selisih',
        'jenis_kriteria',
    ];

    protected $dates = ['deleted_at'];

    // ================= RELASI =================

    // ke hasil perhitungan
    public function hasil()
    {
        return $this->belongsTo(HasilPerhitungan::class, 'id_hasil_perhitungan');
    }
}
