<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BobotSelisih extends Model
{
    use SoftDeletes;

    protected $table = 'bobot_selisih';
    protected $primaryKey = 'id_bobot_selisih';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_bobot_selisih',
        'selisih',
        'bobot_nilai',
        'keterangan',
    ];

    protected $casts = [
        'selisih' => 'integer',
        'bobot_nilai' => 'decimal:2',
    ];

    public static function generateId()
    {
        $year = date('Y');

        $count = self::whereYear('created_at', $year)->count();
        $urut = str_pad($count + 1, 6, '0', STR_PAD_LEFT);

        $id = "BS-{$year}-{$urut}";

        while (self::where('id_bobot_selisih', $id)->exists()) {
            $count++;
            $urut = str_pad($count + 1, 6, '0', STR_PAD_LEFT);
            $id = "BS-{$year}-{$urut}";
        }

        return $id;
    }
}
