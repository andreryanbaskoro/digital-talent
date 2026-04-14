<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfilPerusahaan extends Model
{
    use SoftDeletes;

    protected $table = 'profil_perusahaan';
    protected $primaryKey = 'id_perusahaan';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_perusahaan',
        'id_pengguna',
        'nama_perusahaan',
        'nib',
        'npwp',
        'alamat',
        'kabupaten',
        'provinsi',
        'nomor_telepon',
        'website',
        'logo',
        'deskripsi',
    ];

    protected $dates = ['deleted_at'];

    // ================= RELASI =================

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function lowongan()
    {
        return $this->hasMany(LowonganPekerjaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    // ================= AUTO GENERATE ID =================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $last = self::withTrashed()
                ->selectRaw("MAX(CAST(SUBSTRING(id_perusahaan, 5) AS UNSIGNED)) as max_id")
                ->first();

            $number = $last->max_id ? $last->max_id + 1 : 1;

            $model->id_perusahaan = 'PER-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }
}
