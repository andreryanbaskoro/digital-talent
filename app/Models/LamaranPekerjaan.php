<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LamaranPekerjaan extends Model
{
    use SoftDeletes;

    protected $table = 'lamaran_pekerjaan';
    protected $primaryKey = 'id_lamaran_pekerjaan';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_lowongan_pekerjaan',
        'id_pencari_kerja',
        'tanggal_lamar',
        'status_lamaran',
        'catatan_perusahaan',
    ];

    protected $dates = [
        'tanggal_lamar',
        'deleted_at'
    ];

    // ================= RELASI =================

    // ke lowongan
    public function lowongan()
    {
        return $this->belongsTo(LowonganPekerjaan::class, 'id_lowongan', 'id_lowongan');
    }

    // ke profil pencari kerja
    public function pencariKerja()
    {
        return $this->belongsTo(ProfilPencariKerja::class, 'id_pencari_kerja');
    }

    // ke dokumen lamaran
    public function dokumen()
    {
        return $this->hasMany(DokumenLamaran::class, 'id_lamaran_pekerjaan');
    }

    // ke hasil perhitungan (SPK)
    public function hasilPerhitungan()
    {
        return $this->hasOne(HasilPerhitungan::class, 'id_lamaran_pekerjaan');
    }
}
