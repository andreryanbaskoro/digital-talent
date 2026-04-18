<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubKriteria extends Model
{
    use SoftDeletes;

    protected $table = 'sub_kriteria';
    protected $primaryKey = 'id_sub_kriteria';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_kriteria',
        'nama_sub_kriteria'
    ];

    protected $dates = ['deleted_at'];

    // ================= RELASI =================

    public function kriteria()
    {
        return $this->belongsTo(KriteriaLowongan::class, 'id_kriteria', 'id_kriteria');
    }

    public function lowongan()
    {
        return $this->hasMany(SubKriteriaLowongan::class, 'id_sub_kriteria', 'id_sub_kriteria');
    }

    public function pencaker()
    {
        return $this->hasMany(SubKriteriaPencaker::class, 'id_sub_kriteria', 'id_sub_kriteria');
    }

    // ================= AUTO GENERATE ID =================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $year = date('Y');

            $last = self::withTrashed()
                ->orderBy('id_sub_kriteria', 'desc')
                ->first();

            if ($last) {
                $lastNumber = (int) substr($last->id_sub_kriteria, -5);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $model->id_sub_kriteria = "SKL-$year-" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        });
    }
}
