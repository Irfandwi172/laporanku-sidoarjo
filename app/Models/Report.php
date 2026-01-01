<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    
    protected $fillable = [
    'nama_pelapor',
    'nomor_hp',
    'email',
    'lokasi',
    'latitude',
    'longitude',
    'alamat_lengkap',
    'deskripsi',
    'foto',
    'status',
    'catatan_admin',
    'alasan_penolakan', // TAMBAHKAN INI
    'estimasi_durasi',
    'tanggal_mulai_perbaikan',
    'tanggal_selesai_perbaikan',
    'tingkat_kerusakan',
    'lokasi_strategis',
    'jumlah_pengguna',
    'kedekatan_fasum',
    'skor_saw',
    'prioritas'
];

    protected $casts = [
        'tanggal_mulai_perbaikan' => 'date',
        'tanggal_selesai_perbaikan' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // ============================================
    // SAW (Simple Additive Weighting) Constants
    // ============================================

    // Konstanta untuk bobot kriteria SAW
    const BOBOT = [
        'tingkat_kerusakan' => 0.4,
        'lokasi_strategis' => 0.3,
        'jumlah_pengguna' => 0.2,
        'kedekatan_fasum' => 0.1
    ];

    // Nilai maksimum tiap kriteria
    const MAX_VALUES = [
        'tingkat_kerusakan' => 5,
        'lokasi_strategis' => 5,
        'jumlah_pengguna' => 5,
        'kedekatan_fasum' => 5
    ];

    // Label untuk tingkat kerusakan
    const TINGKAT_KERUSAKAN = [
        1 => 'Sangat Ringan',
        2 => 'Ringan',
        3 => 'Sedang',
        4 => 'Berat',
        5 => 'Sangat Berat'
    ];

    // Label untuk lokasi strategis
    const LOKASI_STRATEGIS = [
        1 => 'Jalan Desa',
        2 => 'Jalan Penghubung Desa',
        3 => 'Perempatan',
        4 => 'Jalan Utama',
        5 => 'Jalan Protokol'
    ];

    // Label untuk jumlah pengguna
    const JUMLAH_PENGGUNA = [
        1 => 'Sangat Sepi',
        2 => 'Sepi',
        3 => 'Sedang',
        4 => 'Ramai',
        5 => 'Sangat Ramai'
    ];

    // Label untuk kedekatan fasilitas umum
    const KEDEKATAN_FASUM = [
        1 => 'Sangat Jauh (>2km)',
        2 => 'Jauh (1-2km)',
        3 => 'Sedang (500m-1km)',
        4 => 'Dekat (100-500m)',
        5 => 'Sangat Dekat (<100m)'
    ];

    // ============================================
    // Existing Methods (Status & Duration)
    // ============================================

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Menunggu Verifikasi' => 'bg-warning text-dark',
            'Diverifikasi' => 'bg-info text-white',
            'Dalam Perbaikan' => 'bg-primary text-white',
            'Selesai' => 'bg-success text-white',
            'Ditolak' => 'bg-danger text-white'
        ];

        return $badges[$this->status] ?? 'bg-secondary text-white';
    }

    public function getDurasiPenangananAttribute()
    {
        if ($this->tanggal_mulai_perbaikan && $this->tanggal_selesai_perbaikan) {
            return $this->tanggal_mulai_perbaikan->diffInDays($this->tanggal_selesai_perbaikan);
        }
        return null;
    }

    // Method untuk mendapatkan Google Maps URL
    public function getGoogleMapsUrlAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        return null;
    }

    // Method untuk cek apakah ada koordinat GPS
    public function hasGpsCoordinates()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    // ============================================
    // SAW Methods
    // ============================================

    /**
     * Hitung skor SAW untuk laporan ini
     */
    public function hitungSkorSAW()
    {
        // Validasi: semua kriteria harus diisi
        if ($this->tingkat_kerusakan == 0 || 
            $this->lokasi_strategis == 0 || 
            $this->jumlah_pengguna == 0 || 
            $this->kedekatan_fasum == 0) {
            return null;
        }

        $skor = 0;
        
        // Normalisasi dan kalikan dengan bobot
        $skor += ($this->tingkat_kerusakan / self::MAX_VALUES['tingkat_kerusakan']) * self::BOBOT['tingkat_kerusakan'];
        $skor += ($this->lokasi_strategis / self::MAX_VALUES['lokasi_strategis']) * self::BOBOT['lokasi_strategis'];
        $skor += ($this->jumlah_pengguna / self::MAX_VALUES['jumlah_pengguna']) * self::BOBOT['jumlah_pengguna'];
        $skor += ($this->kedekatan_fasum / self::MAX_VALUES['kedekatan_fasum']) * self::BOBOT['kedekatan_fasum'];
        
        $this->skor_saw = round($skor, 4);
        $this->save();
        
        return $this->skor_saw;
    }

    /**
     * Get label untuk tingkat kerusakan
     */
    public function getTingkatKerusakanLabelAttribute()
    {
        return self::TINGKAT_KERUSAKAN[$this->tingkat_kerusakan] ?? '-';
    }

    /**
     * Get label untuk lokasi strategis
     */
    public function getLokasiStrategisLabelAttribute()
    {
        return self::LOKASI_STRATEGIS[$this->lokasi_strategis] ?? '-';
    }

    /**
     * Get label untuk jumlah pengguna
     */
    public function getJumlahPenggunaLabelAttribute()
    {
        return self::JUMLAH_PENGGUNA[$this->jumlah_pengguna] ?? '-';
    }

    /**
     * Get label untuk kedekatan fasum
     */
    public function getKedekatanFasumLabelAttribute()
    {
        return self::KEDEKATAN_FASUM[$this->kedekatan_fasum] ?? '-';
    }

    /**
     * Check if report has SAW criteria filled
     */
    public function hasSAWCriteria()
    {
        return $this->tingkat_kerusakan > 0 && 
               $this->lokasi_strategis > 0 && 
               $this->jumlah_pengguna > 0 && 
               $this->kedekatan_fasum > 0;
    }

    /**
     * Get prioritas badge color
     */
    public function getPrioritasBadgeAttribute()
    {
        if (!$this->prioritas) return 'bg-secondary';
        
        if ($this->prioritas == 1) return 'bg-danger';
        if ($this->prioritas == 2) return 'bg-warning';
        if ($this->prioritas == 3) return 'bg-info';
        return 'bg-secondary';
    }

    /**
     * Scope untuk filter laporan yang sudah memiliki kriteria SAW
     */
    public function scopeWithSAW($query)
    {
        return $query->where('tingkat_kerusakan', '>', 0)
                    ->where('lokasi_strategis', '>', 0)
                    ->where('jumlah_pengguna', '>', 0)
                    ->where('kedekatan_fasum', '>', 0);
    }

    /**
     * Scope untuk filter laporan berdasarkan prioritas
     */
    public function scopePrioritas($query)
    {
        return $query->whereNotNull('prioritas')
                    ->orderBy('prioritas', 'asc');
    }
}