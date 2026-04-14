<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HasilPerhitungan extends Model
{
    use SoftDeletes;

    protected $table = 'hasil_perhitungan';
    protected $primaryKey = 'id_hasil_perhitungan';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_lamaran_pekerjaan',
        'nilai_faktor_inti',
        'nilai_faktor_pendukung',
        'nilai_total',
        'peringkat',
        'rekomendasi',
    ];

    protected $dates = ['deleted_at'];

    // ================= RELASI =================

    // ke lamaran pekerjaan
    public function lamaran()
    {
        return $this->belongsTo(LamaranPekerjaan::class, 'id_lamaran_pekerjaan');
    }

    // ke detail perhitungan
    public function detail()
    {
        return $this->hasMany(DetailPerhitungan::class, 'id_hasil_perhitungan');
    }
    
}
