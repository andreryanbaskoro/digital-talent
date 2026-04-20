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
        'id_lamaran',
        'id_sub_kriteria',
        'nilai'
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

    // ================= AUTO GENERATE ID =================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $lamaranId = $model->id_lamaran;

            if (!$lamaranId) {
                throw new \Exception("ID Lamaran tidak ditemukan saat generate Sub Kriteria Lamaran.");
            }

            /**
             * CONTOH:
             * LMR-2026-00001
             */

            $parts = explode('-', $lamaranId);

            if (count($parts) < 3) {
                throw new \Exception("Format ID Lamaran tidak valid.");
            }

            $tahun = $parts[1]; // 2026
            $urut  = $parts[2]; // 00001

            // ambil data terakhir dalam lamaran ini
            $last = self::where('id_lamaran', $lamaranId)
                ->orderBy('id_sub_kriteria_lamaran', 'desc')
                ->first();

            if ($last) {
                $lastNumber = (int) substr($last->id_sub_kriteria_lamaran, -2);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $model->id_sub_kriteria_lamaran =
                "LMR-$tahun-$urut-SKL-" . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        });
    }
}
