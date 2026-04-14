<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';

    public $incrementing = false;
    protected $keyType = 'string';

    // kolom yang boleh diisi
    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'peran',
        'status',
    ];

    // sembunyikan saat di-return
    protected $hidden = [
        'kata_sandi',
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $tanggal = now()->format('ymd'); // 260410

            $last = self::withTrashed()
                ->where('id_pengguna', 'like', 'USR-' . $tanggal . '-%')
                ->selectRaw("MAX(CAST(SUBSTRING(id_pengguna, 12) AS UNSIGNED)) as max_id")
                ->first();

            $number = $last && $last->max_id ? $last->max_id + 1 : 1;

            $model->id_pengguna = 'USR-' . $tanggal . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }

    // ================= AUTH =================

    public function getAuthIdentifierName()
    {
        return 'id_pengguna';
    }

    // karena kamu pakai "kata_sandi", bukan "password"
    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    // ================= RELASI =================

    // ke profil perusahaan
    public function profilPerusahaan()
    {
        return $this->hasOne(ProfilPerusahaan::class, 'id_pengguna');
    }

    // ke profil pencari kerja
    public function profilPencariKerja()
    {
        return $this->hasOne(ProfilPencariKerja::class, 'id_pengguna');
    }

    // ke notifikasi
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_pengguna');
    }

    // ke log aktivitas
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'id_pengguna');
    }
}
