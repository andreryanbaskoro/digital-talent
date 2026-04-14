<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanAk1 extends Model
{
    use SoftDeletes;

    protected $table = 'laporan_ak1';
    protected $primaryKey = 'id_laporan_ak1';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_kartu_ak1',
        'laporan_ke',
        'tanggal_lapor',
        'diterima_di',
        'mulai_berlaku',
        'tanda_tangan_petugas',
    ];

    protected $casts = [
        'laporan_ke' => 'integer',
        'tanggal_lapor' => 'date',
        'mulai_berlaku' => 'date',
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
}
