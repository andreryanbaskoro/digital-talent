<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KeterampilanAk1 extends Model
{
    use SoftDeletes;

    protected $table = 'keterampilan_ak1';
    protected $primaryKey = 'id_keterampilan';

    public $incrementing = false; // penting!
    protected $keyType = 'string'; // karena varchar

    protected $fillable = [
        'id_keterampilan',
        'id_kartu_ak1',
        'nama_keterampilan',
        'tingkat',
        'sertifikat',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $user = Auth::user();

            if (!$user) {
                throw new \Exception('User tidak login');
            }

            // =========================
            // BASE ID USER
            // =========================
            $baseId = $user->id_pengguna;

            // validasi format
            if (!preg_match('/^USR-\d{6}-\d{5}$/', $baseId)) {
                throw new \Exception('Format id_pengguna tidak valid');
            }

            // =========================
            // HITUNG URUTAN PER KARTU AK1
            // =========================
            $last = self::where('id_kartu_ak1', $model->id_kartu_ak1)
                ->withTrashed()
                ->orderByDesc('id_keterampilan')
                ->first();

            if ($last) {
                $lastNumber = (int) substr($last->id_keterampilan, -2);
                $urutan = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
            } else {
                $urutan = '01';
            }

            // =========================
            // GENERATE ID FINAL
            // =========================
            $model->id_keterampilan =
                "{$baseId}-SKL-{$urutan}";
        });
    }

    public function kartuAk1()
    {
        return $this->belongsTo(KartuAk1::class, 'id_kartu_ak1', 'id_kartu_ak1');
    }
}
