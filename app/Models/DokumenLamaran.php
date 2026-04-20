<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class DokumenLamaran extends Model
{
    use SoftDeletes;

    protected $table = 'dokumen_lamaran';
    protected $primaryKey = 'id_dokumen';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_dokumen',
        'id_lamaran',
        'jenis_dokumen',
        'lokasi_file',
    ];

    public static function generateId($idLamaran)
    {
        $tahun = date('Y');

        $last = DB::table('dokumen_lamaran')
            ->where('id_lamaran', $idLamaran)
            ->orderBy('id_dokumen', 'desc')
            ->first();

        $next = $last
            ? ((int) substr($last->id_dokumen, -2) + 1)
            : 1;

        $parts = explode('-', $idLamaran);
        $urutLamaran = $parts[2] ?? '00000';

        return "LMR-$tahun-$urutLamaran-DOK-" . str_pad($next, 2, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_dokumen)) {
                $model->id_dokumen = self::generateId($model->id_lamaran);
            }
        });

        // ✅ HAPUS FILE SAAT FORCE DELETE SAJA
        static::forceDeleted(function ($model) {
            if ($model->lokasi_file && Storage::disk('public')->exists($model->lokasi_file)) {
                Storage::disk('public')->delete($model->lokasi_file);
            }
        });
    }

    public function lamaran()
    {
        return $this->belongsTo(LamaranPekerjaan::class, 'id_lamaran', 'id_lamaran');
    }
}
