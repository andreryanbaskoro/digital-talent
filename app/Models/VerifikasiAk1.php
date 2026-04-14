<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifikasiAk1 extends Model
{
    use SoftDeletes;

    protected $table = 'verifikasi_ak1';
    protected $primaryKey = 'id_verifikasi_ak1';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_kartu_ak1',
        'id_pengguna',
        'status_verifikasi',
        'tanggal_verifikasi',
        'catatan',
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // ke kartu AK1
    public function kartuAk1()
    {
        return $this->belongsTo(KartuAk1::class, 'id_kartu_ak1', 'id_kartu_ak1');
    }

    // ke pengguna (petugas)
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
