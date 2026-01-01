<?php

namespace App\Services;

use App\Models\Report;

class SAWService
{
    /**
     * Hitung ulang semua prioritas menggunakan metode SAW
     */
    public static function hitungSemuaPrioritas()
{
    // Ambil semua laporan yang sudah diverifikasi dan belum selesai
    // EXCLUDE Ditolak dan Selesai
    $reports = Report::whereIn('status', ['Diverifikasi', 'Dalam Perbaikan'])
        ->where('tingkat_kerusakan', '>', 0)
        ->where('lokasi_strategis', '>', 0)
        ->where('jumlah_pengguna', '>', 0)
        ->where('kedekatan_fasum', '>', 0)
        ->get();

    foreach ($reports as $report) {
        $report->hitungSkorSAW();
    }

    $sortedReports = $reports->sortByDesc('skor_saw')->values();

    foreach ($sortedReports as $index => $report) {
        $report->prioritas = $index + 1;
        $report->save();
    }

    return $sortedReports;
}

    /**
     * Get laporan berdasarkan prioritas
     */
    public static function getLaporanByPrioritas($limit = null)
    {
        $query = Report::whereIn('status', ['Diverifikasi', 'Dalam Perbaikan'])
            ->whereNotNull('prioritas')
            ->orderBy('prioritas', 'asc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get detail perhitungan SAW untuk satu laporan
     */
    public static function getDetailPerhitungan($reportId)
    {
        $report = Report::findOrFail($reportId);
        
        // Validasi: pastikan kriteria sudah diisi
        if (!$report->hasSAWCriteria()) {
            return null;
        }
        
        $detail = [
            'kriteria' => [
                'tingkat_kerusakan' => [
                    'nilai' => $report->tingkat_kerusakan,
                    'label' => $report->tingkat_kerusakan_label,
                    'normalisasi' => $report->tingkat_kerusakan / Report::MAX_VALUES['tingkat_kerusakan'],
                    'bobot' => Report::BOBOT['tingkat_kerusakan'],
                    'skor' => ($report->tingkat_kerusakan / Report::MAX_VALUES['tingkat_kerusakan']) * Report::BOBOT['tingkat_kerusakan']
                ],
                'lokasi_strategis' => [
                    'nilai' => $report->lokasi_strategis,
                    'label' => $report->lokasi_strategis_label,
                    'normalisasi' => $report->lokasi_strategis / Report::MAX_VALUES['lokasi_strategis'],
                    'bobot' => Report::BOBOT['lokasi_strategis'],
                    'skor' => ($report->lokasi_strategis / Report::MAX_VALUES['lokasi_strategis']) * Report::BOBOT['lokasi_strategis']
                ],
                'jumlah_pengguna' => [
                    'nilai' => $report->jumlah_pengguna,
                    'label' => $report->jumlah_pengguna_label,
                    'normalisasi' => $report->jumlah_pengguna / Report::MAX_VALUES['jumlah_pengguna'],
                    'bobot' => Report::BOBOT['jumlah_pengguna'],
                    'skor' => ($report->jumlah_pengguna / Report::MAX_VALUES['jumlah_pengguna']) * Report::BOBOT['jumlah_pengguna']
                ],
                'kedekatan_fasum' => [
                    'nilai' => $report->kedekatan_fasum,
                    'label' => $report->kedekatan_fasum_label,
                    'normalisasi' => $report->kedekatan_fasum / Report::MAX_VALUES['kedekatan_fasum'],
                    'bobot' => Report::BOBOT['kedekatan_fasum'],
                    'skor' => ($report->kedekatan_fasum / Report::MAX_VALUES['kedekatan_fasum']) * Report::BOBOT['kedekatan_fasum']
                ]
            ],
            'skor_total' => $report->skor_saw,
            'prioritas' => $report->prioritas
        ];

        return $detail;
    }

    /**
     * Get statistik SAW untuk dashboard
     */
    public static function getStatistik()
    {
        $stats = [
            'total_dengan_saw' => Report::withSAW()->count(),
            'prioritas_tinggi' => Report::where('prioritas', '<=', 5)->count(),
            'rata_rata_skor' => round(Report::withSAW()->avg('skor_saw'), 4),
            'skor_tertinggi' => Report::withSAW()->max('skor_saw'),
            'skor_terendah' => Report::withSAW()->min('skor_saw'),
        ];

        return $stats;
    }

    /**
     * Get laporan dengan prioritas tertinggi berdasarkan lokasi
     */
    public static function getPrioritasByLokasi($lokasi)
    {
        return Report::where('lokasi', 'LIKE', "%{$lokasi}%")
            ->whereNotNull('prioritas')
            ->orderBy('prioritas', 'asc')
            ->get();
    }

    /**
     * Get distribusi prioritas
     */
    public static function getDistribusiPrioritas()
    {
        return [
            'prioritas_1_5' => Report::whereBetween('prioritas', [1, 5])->count(),
            'prioritas_6_10' => Report::whereBetween('prioritas', [6, 10])->count(),
            'prioritas_11_20' => Report::whereBetween('prioritas', [11, 20])->count(),
            'prioritas_lebih_20' => Report::where('prioritas', '>', 20)->count(),
        ];
    }

    /**
     * Validasi kriteria SAW
     */
    public static function validateKriteria($tingkat_kerusakan, $lokasi_strategis, $jumlah_pengguna, $kedekatan_fasum)
    {
        $errors = [];

        if ($tingkat_kerusakan < 1 || $tingkat_kerusakan > 5) {
            $errors[] = 'Tingkat kerusakan harus antara 1-5';
        }

        if ($lokasi_strategis < 1 || $lokasi_strategis > 5) {
            $errors[] = 'Lokasi strategis harus antara 1-5';
        }

        if ($jumlah_pengguna < 1 || $jumlah_pengguna > 5) {
            $errors[] = 'Jumlah pengguna harus antara 1-5';
        }

        if ($kedekatan_fasum < 1 || $kedekatan_fasum > 5) {
            $errors[] = 'Kedekatan fasilitas umum harus antara 1-5';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Simulasi perhitungan SAW tanpa menyimpan ke database
     */
    public static function simulasiSkor($tingkat_kerusakan, $lokasi_strategis, $jumlah_pengguna, $kedekatan_fasum)
    {
        $skor = 0;
        
        $skor += ($tingkat_kerusakan / Report::MAX_VALUES['tingkat_kerusakan']) * Report::BOBOT['tingkat_kerusakan'];
        $skor += ($lokasi_strategis / Report::MAX_VALUES['lokasi_strategis']) * Report::BOBOT['lokasi_strategis'];
        $skor += ($jumlah_pengguna / Report::MAX_VALUES['jumlah_pengguna']) * Report::BOBOT['jumlah_pengguna'];
        $skor += ($kedekatan_fasum / Report::MAX_VALUES['kedekatan_fasum']) * Report::BOBOT['kedekatan_fasum'];
        
        return round($skor, 4);
    }
}