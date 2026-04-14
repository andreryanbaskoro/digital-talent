<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class LogAktivitas extends Model
{
    use SoftDeletes;

    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id_log';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id_log',
        'id_pengguna',
        'aktivitas',
        'data_lama',
        'data_baru',
        'alamat_ip',
        'created_at',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
        'created_at' => 'datetime',
    ];

    /*
    |------------------------------------------------------------------
    | AUTO GENERATE ID
    |------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $tanggal = now()->format('Ymd');

            $last = DB::table('log_aktivitas')
                ->whereDate('created_at', now()->toDateString())
                ->where('id_log', 'like', "LOG-$tanggal-%")
                ->orderBy('id_log', 'desc')
                ->first();

            if ($last) {
                $lastNumber = (int) substr($last->id_log, -5);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $urutan = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $model->id_log = "LOG-$tanggal-$urutan";

            if (!$model->created_at) {
                $model->created_at = now();
            }
        });
    }

    /*
    |------------------------------------------------------------------
    | RELATIONSHIPS
    |------------------------------------------------------------------
    */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
