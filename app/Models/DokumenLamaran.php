<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenLamaran extends Model
{
    use SoftDeletes;

    protected $table = 'dokumen_lamaran';
    protected $primaryKey = 'id_dokumen_lamaran';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_lamaran_pekerjaan',
        'jenis_dokumen',
        'lokasi_file',
    ];

    protected $dates = ['deleted_at'];

    // ================= RELASI =================

    // ke lamaran pekerjaan
    public function lamaran()
    {
        return $this->belongsTo(LamaranPekerjaan::class, 'id_lamaran_pekerjaan');
    }
}
