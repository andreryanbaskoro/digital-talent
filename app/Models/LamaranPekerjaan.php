<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LamaranPekerjaan extends Model
{
    use SoftDeletes;

    protected $table = 'lamaran_pekerjaan';
    protected $primaryKey = 'id_lamaran';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_lamaran',
        'id_lowongan',
        'id_pencari_kerja',
        'tanggal_lamar',
        'status_lamaran',
        'catatan_perusahaan',
    ];

    protected $dates = [
        'tanggal_lamar',
        'deleted_at'
    ];

    protected $casts = [
        'tanggal_lamar' => 'datetime',
    ];

    // ================= RELASI =================

    public function lowongan()
    {
        return $this->belongsTo(
            LowonganPekerjaan::class,
            'id_lowongan',
            'id_lowongan'
        );
    }

    public function pencariKerja()
    {
        return $this->belongsTo(
            ProfilPencariKerja::class,
            'id_pencari_kerja',
            'id_pencari_kerja'
        );
    }

    public function dokumen()
    {
        return $this->hasMany(
            DokumenLamaran::class,
            'id_lamaran',
            'id_lamaran'
        );
    }

    public function hasilPerhitungan()
    {
        return $this->hasOne(
            HasilPerhitungan::class,
            'id_lamaran',
            'id_lamaran'
        );
    }

    // ================= ID GENERATOR =================

    public static function generateId($idLowongan)
    {
        $year = date('Y');

        // urutan lamaran tahun ini
        $count = self::whereYear('created_at', $year)->count();
        $urutLamaran = str_pad($count + 1, 5, '0', STR_PAD_LEFT);

        // ambil urutan lowongan (fallback dari ID)
        $urutLowongan = substr($idLowongan, -5);

        $id = "LOW-{$year}-{$urutLowongan}-LMR-{$year}-{$urutLamaran}";

        // pastikan tidak duplicate
        while (self::where('id_lamaran', $id)->exists()) {
            $count++;
            $urutLamaran = str_pad($count + 1, 5, '0', STR_PAD_LEFT);

            $id = "LOW-{$year}-{$urutLowongan}-LMR-{$year}-{$urutLamaran}";
        }

        return $id;
    }

    public function subKriteriaLamaran()
    {
        return $this->hasMany(
            SubKriteriaLamaran::class,
            'id_lamaran',
            'id_lamaran'
        );
    }
}
