<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class KartuAk1 extends Model
{
    use SoftDeletes;

    protected $table = 'kartu_ak1';
    protected $primaryKey = 'id_kartu_ak1';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_kartu_ak1',
        'id_pencari_kerja',
        'nomor_pendaftaran',
        'tanggal_daftar',
        'berlaku_mulai',
        'berlaku_sampai',
        'status',
        'is_revised',
        'foto_pas',
        'scan_ktp',
        'scan_ijazah',
        'scan_kk',
        'catatan_petugas',
        'nama_petugas',
        'nip_petugas',
        'submitted_at',
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
        'berlaku_mulai' => 'date',
        'berlaku_sampai' => 'date',
        'submitted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------
    | GENERATE ID AK1-YYYY-00001
    |--------------------------------------------------
    */
    public static function generateId()
    {
        $tahun = now()->format('Y');

        $last = DB::table('kartu_ak1')
            ->where('id_kartu_ak1', 'like', "AK1-$tahun-%")
            ->orderBy('id_kartu_ak1', 'desc')
            ->first();

        $nextNumber = $last
            ? ((int) substr($last->id_kartu_ak1, -5) + 1)
            : 1;

        return "AK1-$tahun-" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function markAsRevised()
    {
        $this->update([
            'status' => 'pending',
            'is_revised' => 1, // selalu revisi kalau submit ulang
            'berlaku_mulai' => null,
            'berlaku_sampai' => null,
        ]);
    }

    /*
    |--------------------------------------------------
    | BOOT MODEL
    |--------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            if (empty($model->id_kartu_ak1)) {
                $model->id_kartu_ak1 = self::generateId();
            }

            if (empty($model->tanggal_daftar)) {
                $model->tanggal_daftar = now();
            }

            if (empty($model->status)) {
                $model->status = 'pending';
            }
        });

        /*
        |--------------------------------------------------
        | AUTO NOMOR PENDAFTARAN (CREATE + UPDATE)
        |--------------------------------------------------
        */
        static::saving(function ($model) {

            if (empty($model->nomor_pendaftaran) && !empty($model->id_kartu_ak1)) {
                $parts = explode('-', $model->id_kartu_ak1);

                $tahun = $parts[1] ?? now()->format('Y');
                $urut  = $parts[2] ?? '00000';

                $model->nomor_pendaftaran = $tahun . '-' . $urut;
            }
        });
    }

    /*
    |--------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------
    */

    public function profilPencariKerja()
    {
        return $this->belongsTo(
            ProfilPencariKerja::class,
            'id_pencari_kerja',
            'id_pencari_kerja'
        );
    }

    public function keterampilan()
    {
        return $this->hasMany(KeterampilanAk1::class, 'id_kartu_ak1');
    }

    public function pengalamanKerja()
    {
        return $this->hasMany(PengalamanKerjaAk1::class, 'id_kartu_ak1');
    }

    public function riwayatPendidikan()
    {
        return $this->hasMany(RiwayatPendidikanAk1::class, 'id_kartu_ak1');
    }

    public function laporan()
    {
        return $this->hasMany(LaporanAk1::class, 'id_kartu_ak1');
    }

    public function verifikasi()
    {
        return $this->hasMany(VerifikasiAk1::class, 'id_kartu_ak1');
    }

    public function latestVerifikasi()
    {
        return $this->hasOne(VerifikasiAk1::class, 'id_kartu_ak1', 'id_kartu_ak1')
            ->latestOfMany('tanggal_verifikasi');
    }
}
