<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifikasiAk1 extends Model
{
    use SoftDeletes;

    protected $table = 'verifikasi_ak1';
    protected $primaryKey = 'id_verifikasi_ak1';

    // karena PK bukan auto increment
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_verifikasi_ak1',
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
    | AUTO GENERATE ID
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id_verifikasi_ak1) {
                $model->id_verifikasi_ak1 = self::generateId();
            }
        });
    }

    public static function generateId(): string
    {
        $year = now()->format('Y');

        $last = self::where('id_verifikasi_ak1', 'like', "VRF-$year-%")
            ->orderBy('id_verifikasi_ak1', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->id_verifikasi_ak1, -5);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $number = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return "VRF-$year-$number";
    }

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

    // ke petugas / pengguna
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
