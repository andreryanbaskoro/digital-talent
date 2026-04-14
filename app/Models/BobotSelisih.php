<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BobotSelisih extends Model
{
    use SoftDeletes;

    protected $table = 'bobot_selisih';
    protected $primaryKey = 'id_bobot_selisih';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'selisih',
        'bobot_nilai',
        'keterangan',
    ];

    protected $casts = [
        'selisih' => 'decimal:2',
        'bobot_nilai' => 'decimal:2',
    ];
}
