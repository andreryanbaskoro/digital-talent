<?php

namespace App\Observers;

use App\Models\LamaranPekerjaan;
use App\Services\ProfileMatchingService;
use App\Models\HasilPerhitungan;
use App\Models\DetailPerhitungan;
use Illuminate\Support\Facades\DB;

class LamaranObserver
{
    public function created(LamaranPekerjaan $lamaran)
    {
        $service = app(ProfileMatchingService::class);

        $ranking = $service->rankingLowongan($lamaran->id_lowongan);

        if (empty($ranking)) {
            return;
        }

        DB::transaction(function () use ($ranking) {

            foreach ($ranking as $data) {

                $hasil = HasilPerhitungan::firstOrNew([
                    'id_lamaran' => $data['id_lamaran'],
                ]);

                if (!$hasil->exists) {
                    $hasil->id_hasil = HasilPerhitungan::generateId();
                }

                $cf = ($data['skill'] + $data['pengalaman']) / 2;
                $sf = ($data['pendidikan'] + $data['lokasi']) / 2;

                $hasil->fill([
                    'nilai_faktor_inti' => round($cf, 2),
                    'nilai_faktor_pendukung' => round($sf, 2),
                    'nilai_total' => $data['total_nilai'] ?? 0,
                    'peringkat' => $data['ranking'] ?? 0,
                    'rekomendasi' => $data['persentase'] >= 85
                        ? '⭐ Sangat Cocok'
                        : ($data['persentase'] >= 70 ? '👍 Cocok' : '❗ Kurang Cocok'),
                ]);

                $hasil->save();

                DetailPerhitungan::where('id_hasil', $hasil->id_hasil)->forceDelete();
            }
        });
    }
}
