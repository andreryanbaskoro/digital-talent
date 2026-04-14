<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengaturanSistem extends Model
{
    use SoftDeletes;

    protected $table = 'pengaturan_sistem';
    protected $primaryKey = 'id_pengaturan_sistem';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_instansi',
        'alamat_instansi',
        'logo',
        'nomor_awal_ak1',
        'masa_berlaku_ak1',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
