<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifikasi extends Model
{
    use SoftDeletes;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';

    public $incrementing = false;   // ❗ bukan auto increment
    protected $keyType = 'string';  // ❗ string, bukan int

    protected $fillable = [
        'id_notifikasi',
        'id_pengguna',
        'judul',
        'isi_pesan',
        'tipe',
        'status_baca',
    ];

    protected $casts = [
        'status_baca' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            if (empty($model->id_notifikasi)) {

                $year = now()->format('Y');

                $userCode = '00000';
                $dateCode = now()->format('ymd');

                if ($model->id_pengguna) {
                    $pengguna = Pengguna::withTrashed()->find($model->id_pengguna);

                    if ($pengguna) {
                        $dateCode = substr($pengguna->id_pengguna, 4, 6);
                        $userCode = substr($pengguna->id_pengguna, -5);
                    }
                }

                $last = self::withTrashed()
                    ->whereYear('created_at', $year)
                    ->count() + 1;

                $notifUrut = str_pad($last, 5, '0', STR_PAD_LEFT);

                $model->id_notifikasi =
                    "USR-{$dateCode}-{$userCode}-NTF-{$year}-{$notifUrut}";
            }
        });
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
