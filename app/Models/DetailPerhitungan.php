<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPerhitungan extends Model
{
    use SoftDeletes;

    protected $table = 'detail_perhitungan';
    protected $primaryKey = 'id_detail_perhitungan';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_detail_perhitungan',
        'id_hasil',
        'nama_kriteria',
        'nilai_pelamar',
        'nilai_target',
        'selisih',
        'bobot_selisih',
        'jenis_kriteria',
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id_detail_perhitungan) {
                $year = date('Y');

                $last = self::whereYear('created_at', $year)
                    ->orderBy('id_detail_perhitungan', 'desc')
                    ->first();

                if ($last) {
                    $lastNumber = (int) substr($last->id_detail_perhitungan, -5);
                    $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
                } else {
                    $newNumber = '00001';
                }

                $model->id_detail_perhitungan = 'DTL-' . $year . '-' . $newNumber;
            }
        });
    }

    public function hasil()
    {
        return $this->belongsTo(
            HasilPerhitungan::class,
            'id_hasil',   // foreign key di tabel detail
            'id_hasil'    // primary key di tabel hasil
        );
    }
}
