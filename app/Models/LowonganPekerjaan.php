<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class LowonganPekerjaan extends Model
{
    use SoftDeletes;

    protected $table = 'lowongan_pekerjaan';
    protected $primaryKey = 'id_lowongan';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_perusahaan',
        'judul_lowongan',
        'deskripsi',
        'lokasi',
        'jenis_pekerjaan',
        'sistem_kerja',
        'gaji_minimum',
        'gaji_maksimum',
        'pendidikan_minimum',
        'pengalaman_minimum',
        'kuota',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status',
        'catatan',
    ];

    protected $dates = [
        'tanggal_mulai',
        'tanggal_berakhir',
        'deleted_at'
    ];

    public function profilPerusahaan()
    {
        return $this->belongsTo(ProfilPerusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }


    // ke kriteria lowongan
    public function kriteria()
    {
        return $this->hasMany(KriteriaLowongan::class, 'id_lowongan', 'id_lowongan');
    }

    // ke lamaran pekerjaan
    public function lamaran()
    {
        return $this->hasMany(LamaranPekerjaan::class, 'id_lowongan', 'id_lowongan');
    }

    public function subKriteriaLowongan()
    {
        return $this->hasMany(SubKriteriaLowongan::class, 'id_lowongan', 'id_lowongan');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $year = Carbon::now()->format('Y');

            // Ambil ID terakhir berdasarkan tahun yang sama
            $last = self::withTrashed()
                ->where('id_lowongan', 'like', "LOW-$year-%")
                ->orderBy('id_lowongan', 'desc')
                ->first();

            if ($last) {
                // Ambil angka urutan terakhir
                $lastNumber = (int) substr($last->id_lowongan, -5);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            // Format jadi 5 digit (00001)
            $formattedNumber = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            // Gabungkan jadi ID
            $model->id_lowongan = "LOW-$year-$formattedNumber";
        });
    }
}
