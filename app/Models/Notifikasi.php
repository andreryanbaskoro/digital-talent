<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifikasi extends Model
{
    use SoftDeletes;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pengguna',
        'judul',
        'isi_pesan',
        'tipe',
        'status_baca',
    ];

    protected $casts = [
        'status_baca' => 'boolean',
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
}
