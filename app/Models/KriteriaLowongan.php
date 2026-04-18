<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KriteriaLowongan extends Model
{
    use SoftDeletes;

    protected $table = 'kriteria_lowongan';
    protected $primaryKey = 'id_kriteria';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_lowongan',
        'nama_kriteria',
        'jenis_kriteria',
        'bobot',
        'nilai_target',
    ];

    protected $dates = ['deleted_at'];

    // ================= RELASI =================

    public function lowongan()
    {
        return $this->belongsTo(LowonganPekerjaan::class, 'id_lowongan', 'id_lowongan');
    }

    // ================= AUTO GENERATE KODE =================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $lowonganId = $model->id_lowongan;

            if (!$lowonganId) {
                throw new \Exception("ID Lowongan tidak ditemukan saat generate Kriteria.");
            }

            // Contoh ID: LOW-2026-00003
            $parts = explode('-', $lowonganId);

            if (count($parts) < 3) {
                throw new \Exception("Format ID Lowongan tidak valid.");
            }

            $tahun = $parts[1];        // 2026
            $urutLowongan = $parts[2]; // 00003

            // Cari kriteria terakhir dalam lowongan ini (termasuk soft delete)
            $last = self::withTrashed()
                ->where('id_lowongan', $lowonganId)
                ->orderBy('id_kriteria', 'desc')
                ->first();

            if ($last) {
                // Ambil 2 digit terakhir (format baru)
                $lastNumber = (int) substr($last->id_kriteria, -2);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            // Format 2 digit → 01, 02, 03 ...
            $formattedNumber = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

            // FINAL FORMAT
            $model->id_kriteria = "LOW-$tahun-$urutLowongan-KRL-$formattedNumber";
        });
    }
}
