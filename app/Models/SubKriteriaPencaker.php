<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SubKriteriaPencaker extends Model
{
    protected $table = 'sub_kriteria_pencaker';
    protected $primaryKey = 'id_sub_kriteria_pencaker';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pencari_kerja',
        'id_sub_kriteria',
        'nilai'
    ];

    // ================= RELASI =================

    public function pencaker()
    {
        return $this->belongsTo(ProfilPencariKerja::class, 'id_pencari_kerja', 'id_pencari_kerja');
    }

    public function subKriteria()
    {
        return $this->belongsTo(SubKriteria::class, 'id_sub_kriteria', 'id_sub_kriteria');
    }

    // ================= AUTO GENERATE ID =================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $user = Auth::user();

            if (!$user) {
                throw new \Exception("User tidak ditemukan.");
            }

            $parts = explode('-', $user->id_pengguna);

            if (count($parts) < 3) {
                throw new \Exception("Format ID User tidak valid.");
            }

            $prefix   = $parts[0];
            $tgl      = $parts[1];
            $urutUser = $parts[2];

            $last = self::where('id_pencari_kerja', $model->id_pencari_kerja)
                ->orderBy('id_sub_kriteria_pencaker', 'desc')
                ->first();

            if ($last) {
                $lastNumber = (int) substr($last->id_sub_kriteria_pencaker, -2);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $model->id_sub_kriteria_pencaker =
                "$prefix-$tgl-$urutUser-SKL-" . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        });
    }
}
