<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\SAWService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    public function dashboard()
    {
        try {
            $stats = [
                'total' => Report::count(),
                'menunggu_verifikasi' => Report::where('status', 'Menunggu Verifikasi')->count(),
                'diverifikasi' => Report::where('status', 'Diverifikasi')->count(),
                'dalam_perbaikan' => Report::where('status', 'Dalam Perbaikan')->count(),
                'selesai' => Report::where('status', 'Selesai')->count(),
                'ditolak' => Report::where('status', 'Ditolak')->count(),
            ];

            $recent_reports = Report::orderBy('created_at', 'desc')->take(10)->get();
        } catch (\Exception $e) {
            $stats = [
                'total' => 0,
                'menunggu_verifikasi' => 0,
                'diverifikasi' => 0,
                'dalam_perbaikan' => 0,
                'selesai' => 0,
                'ditolak' => 0,
            ];
            $recent_reports = collect([]);
        }

        return view('petugas.dashboard', compact('stats', 'recent_reports'));
    }

    public function reports(Request $request)
    {
        try {
            $query = Report::query();

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_pelapor', 'LIKE', "%{$search}%")
                        ->orWhere('lokasi', 'LIKE', "%{$search}%")
                        ->orWhere('deskripsi', 'LIKE', "%{$search}%");
                });
            }

            // Order by prioritas
            $reports = $query->orderByRaw('COALESCE(prioritas, 999999) ASC')
                ->orderBy('created_at', 'desc')
                ->paginate(15);

        } catch (\Exception $e) {
            $reports = collect([]);
        }

        return view('petugas.reports', compact('reports'));
    }

    public function show($id)
    {
        $report = Report::findOrFail($id);

        $saw_detail = null;
        if ($report->skor_saw) {
            $saw_detail = SAWService::getDetailPerhitungan($id);
        }

        return view('petugas.show', compact('report', 'saw_detail'));
    }

    public function updateStatus(Request $request, $id)
    {
        // PERBAIKI VALIDASI - TAMBAHKAN DITOLAK
        $request->validate([
            'status' => 'required|in:Menunggu Verifikasi,Diverifikasi,Dalam Perbaikan,Selesai,Ditolak',
            'catatan_petugas' => 'nullable|string',
            'tanggal_mulai_perbaikan' => 'nullable|date',
            'tanggal_selesai_perbaikan' => 'nullable|date|after:tanggal_mulai_perbaikan',
            'alasan_penolakan' => 'required_if:status,Ditolak|nullable|string', // TAMBAHKAN INI
        ]);

        $report = Report::findOrFail($id);

        $updateData = [
            'status' => $request->status,
            'catatan_petugas' => $request->catatan_petugas,
            'tanggal_mulai_perbaikan' => $request->tanggal_mulai_perbaikan,
            'tanggal_selesai_perbaikan' => $request->tanggal_selesai_perbaikan,
        ];

        // TAMBAHKAN HANDLING UNTUK ALASAN PENOLAKAN
        if ($request->status == 'Ditolak') {
            $updateData['alasan_penolakan'] = $request->alasan_penolakan;
            // Hapus prioritas dan skor SAW jika ditolak
            $updateData['skor_saw'] = null;
            $updateData['prioritas'] = null;
        } else {
            // Reset alasan penolakan jika bukan ditolak
            $updateData['alasan_penolakan'] = null;
        }

        $report->update($updateData);

        // Hitung ulang prioritas jika ada perubahan status (kecuali ditolak)
        if ($request->status != 'Ditolak') {
            SAWService::hitungSemuaPrioritas();
        }

        // Pesan sukses yang lebih detail
        $message = 'Status laporan berhasil diupdate';
        if ($request->status == 'Ditolak') {
            $message = 'Laporan berhasil ditolak';
        } elseif ($request->status == 'Selesai') {
            $message = 'Laporan berhasil diselesaikan';
        }

        return redirect()->route('petugas.show', $report->id)
            ->with('success', $message);
    }

    public function updateKriteria(Request $request, $id)
    {
        $request->validate([
            'tingkat_kerusakan' => 'required|integer|min:1|max:5',
            'lokasi_strategis' => 'required|integer|min:1|max:5',
            'jumlah_pengguna' => 'required|integer|min:1|max:5',
            'kedekatan_fasum' => 'required|integer|min:1|max:5',
        ]);

        $report = Report::findOrFail($id);

        // Update kriteria SAW
        $updateData = [
            'tingkat_kerusakan' => $request->tingkat_kerusakan,
            'lokasi_strategis' => $request->lokasi_strategis,
            'jumlah_pengguna' => $request->jumlah_pengguna,
            'kedekatan_fasum' => $request->kedekatan_fasum,
        ];

        $report->update($updateData);

        // Hitung skor SAW untuk laporan ini
        $report->hitungSkorSAW();

        // Hitung ulang prioritas untuk semua laporan
        SAWService::hitungSemuaPrioritas();

        return redirect()->route('petugas.show', $report->id)
            ->with('success', 'Kriteria SAW berhasil disimpan dan prioritas telah dihitung! Prioritas: ' . $report->fresh()->prioritas);
    }

    public function hitungPrioritasSAW()
    {
        try {
            // Ambil semua laporan yang belum ditolak dan belum selesai
            $reports = Report::whereNotIn('status', ['Ditolak', 'Selesai'])
                ->whereNotNull('tingkat_kerusakan')
                ->whereNotNull('lokasi_strategis')
                ->whereNotNull('jumlah_pengguna')
                ->whereNotNull('kedekatan_fasum')
                ->where('tingkat_kerusakan', '>', 0)
                ->where('lokasi_strategis', '>', 0)
                ->where('jumlah_pengguna', '>', 0)
                ->where('kedekatan_fasum', '>', 0)
                ->get();

            if ($reports->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada laporan dengan kriteria lengkap untuk dihitung prioritasnya');
            }

            // Hitung SAW untuk setiap laporan
            foreach ($reports as $report) {
                $report->hitungSkorSAW();
            }

            // Hitung ulang prioritas untuk semua laporan
            SAWService::hitungSemuaPrioritas();

            return redirect()->back()->with('success', 'Prioritas berhasil dihitung untuk ' . $reports->count() . ' laporan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghitung prioritas: ' . $e->getMessage());
        }
    }

    public function prioritas()
    {
        try {
            $reports = SAWService::getLaporanByPrioritas();
        } catch (\Exception $e) {
            $reports = collect([]);
        }

        return view('petugas.prioritas', compact('reports'));
    }
}