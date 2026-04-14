<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class RiwayatPendidikanAk1 extends Model
{
    use SoftDeletes;

    protected $table = 'riwayat_pendidikan_ak1';
    protected $primaryKey = 'id_riwayat_pendidikan';

    public $incrementing = false; // ❗ wajib
    protected $keyType = 'string'; // ❗ karena varchar

    protected $fillable = [
        'id_riwayat_pendidikan',
        'id_kartu_ak1',
        'jenjang',
        'nama_sekolah',
        'jurusan',
        'tahun_masuk',
        'tahun_lulus',
        'nilai_akhir',
    ];

    protected $casts = [
        'tahun_masuk' => 'integer',
        'tahun_lulus' => 'integer',
        'nilai_akhir' => 'decimal:2',
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
            // BASE ID USER
            // =========================
            $baseId = $user->id_pengguna;

            // validasi format (opsional)
            if (!preg_match('/^USR-\d{6}-\d{5}$/', $baseId)) {
                throw new \Exception('Format id_pengguna tidak valid');
            }

            // =========================
            // URUTAN PER KARTU AK1
            // =========================
            $count = self::where('id_kartu_ak1', $model->id_kartu_ak1)->count();

            $urutan = str_pad($count + 1, 2, '0', STR_PAD_LEFT);

            // =========================
            // GENERATE ID FINAL
            // =========================
            $model->id_riwayat_pendidikan =
                "{$baseId}-RPD-{$urutan}";
        });
    }

    public function kartuAk1()
    {
        return $this->belongsTo(KartuAk1::class, 'id_kartu_ak1', 'id_kartu_ak1');
    }
}
