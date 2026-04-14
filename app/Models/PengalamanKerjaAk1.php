<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PengalamanKerjaAk1 extends Model
{
    use SoftDeletes;

    protected $table = 'pengalaman_kerja_ak1';
    protected $primaryKey = 'id_pengalaman_kerja';

    public $incrementing = false; // ❗ wajib
    protected $keyType = 'string'; // ❗ karena varchar

    protected $fillable = [
        'id_pengalaman_kerja',
        'id_kartu_ak1',
        'nama_perusahaan',
        'jabatan',
        'mulai_bekerja',
        'selesai_bekerja',
        'deskripsi',
    ];

    protected $casts = [
        'mulai_bekerja' => 'date',
        'selesai_bekerja' => 'date',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $user = Auth::user();

            if (!$user) {
                throw new \Exception('User tidak login');
            }

            // =========================
            // AMBIL ID PENGGUNA
            // =========================
            // contoh: USR-260410-99999
            $baseId = $user->id_pengguna;

            // validasi format (opsional tapi bagus)
            if (!preg_match('/^USR-\d{6}-\d{5}$/', $baseId)) {
                throw new \Exception('Format id_pengguna tidak valid');
            }

            // =========================
            // HITUNG URUTAN PKJ
            // =========================
            $count = self::where('id_kartu_ak1', $model->id_kartu_ak1)->count();

            $urutan = str_pad($count + 1, 2, '0', STR_PAD_LEFT);

            // =========================
            // GENERATE ID FINAL
            // =========================
            $model->id_pengalaman_kerja = "{$baseId}-PKJ-{$urutan}";
        });
    }

    public function kartuAk1()
    {
        return $this->belongsTo(KartuAk1::class, 'id_kartu_ak1', 'id_kartu_ak1');
    }
}
