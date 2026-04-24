<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HasilPerhitungan extends Model
{
    use SoftDeletes;

    protected $table = 'hasil_perhitungan';
    protected $primaryKey = 'id_hasil';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_hasil',
        'id_lamaran',
        'nilai_faktor_inti',
        'nilai_faktor_pendukung',
        'nilai_total',
        'peringkat',
        'rekomendasi',
    ];

    protected $casts = [
        'nilai_faktor_inti' => 'decimal:2',
        'nilai_faktor_pendukung' => 'decimal:2',
        'nilai_total' => 'decimal:2',
        'peringkat' => 'integer',
    ];

    protected $dates = ['deleted_at'];

    /*
    |--------------------------------------------------------------------------
    | AUTO GENERATE ID
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_hasil)) {
                $model->id_hasil = self::generateId();
            }
        });
    }

    public static function generateId()
    {
        $year = date('Y');

        $count = self::whereYear('created_at', $year)->count();
        $urut = str_pad($count + 1, 6, '0', STR_PAD_LEFT);

        $id = "HSL-{$year}-{$urut}";

        while (self::where('id_hasil', $id)->exists()) {
            $count++;
            $urut = str_pad($count + 1, 6, '0', STR_PAD_LEFT);
            $id = "HSL-{$year}-{$urut}";
        }

        return $id;
    }

    // ================= RELASI =================

    public function lamaran()
    {
        return $this->belongsTo(
            LamaranPekerjaan::class,
            'id_lamaran',
            'id_lamaran'
        );
    }

    public function detail()
    {
        return $this->hasMany(
            DetailPerhitungan::class,
            'id_hasil',   // foreign key di tabel detail
            'id_hasil'    // primary key di tabel hasil
        );
    }
}
