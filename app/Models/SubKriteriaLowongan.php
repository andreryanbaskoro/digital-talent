<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKriteriaLowongan extends Model
{
    protected $table = 'sub_kriteria_lowongan';
    protected $primaryKey = 'id_sub_kriteria_lowongan';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_lowongan',
        'id_sub_kriteria',
        'nilai_target'
    ];

    // ================= RELASI =================

    public function lowongan()
    {
        return $this->belongsTo(LowonganPekerjaan::class, 'id_lowongan', 'id_lowongan');
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

            $lowonganId = $model->id_lowongan;

            if (!$lowonganId) {
                throw new \Exception("ID Lowongan tidak ditemukan.");
            }

            $parts = explode('-', $lowonganId);

            if (count($parts) < 3) {
                throw new \Exception("Format ID Lowongan tidak valid.");
            }

            $tahun = $parts[1];
            $urut  = $parts[2];

            // ambil terakhir berdasarkan PK baru
            $last = self::where('id_lowongan', $lowonganId)
                ->orderBy('id_sub_kriteria_lowongan', 'desc')
                ->first();

            if ($last) {
                $lastNumber = (int) substr($last->id_sub_kriteria_lowongan, -2);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $model->id_sub_kriteria_lowongan =
                "LOW-$tahun-$urut-SKL-" . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        });
    }
}
