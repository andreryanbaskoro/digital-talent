<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfilPencariKerja extends Model
{
    use SoftDeletes;

    protected $table = 'profil_pencari_kerja';
    protected $primaryKey = 'id_pencari_kerja';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pengguna',
        'nik',
        'nomor_kk',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_perkawinan',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'nomor_hp',
        'email',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // ke pengguna
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    // ke lamaran pekerjaan
    public function lamaranPekerjaan()
    {
        return $this->hasMany(LamaranPekerjaan::class, 'id_profil_pencari_kerja', 'id_profil_pencari_kerja');
    }

    // ke kartu AK1
    public function kartuAk1()
    {
        return $this->hasOne(KartuAk1::class, 'id_profil_pencari_kerja', 'id_profil_pencari_kerja');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            // ambil tanggal lahir (format YYMMDD)
            $tgl = date('ymd', strtotime($model->tanggal_lahir));

            // ambil nomor urut terakhir
            $last = self::where('id_pencari_kerja', 'like', "PCK-$tgl-%")
                ->orderBy('id_pencari_kerja', 'desc')
                ->first();

            if ($last) {
                $lastNumber = (int) substr($last->id_pencari_kerja, -5);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $model->id_pencari_kerja =
                'PCK-' . $tgl . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        });
    }
}
