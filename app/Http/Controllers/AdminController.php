<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\SAWService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
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
                'ditolak' => Report::where('status', 'Ditolak')->count(), // TAMBAHKAN INI
            ];

            $recent_reports = Report::orderBy('created_at', 'desc')->take(5)->get();
            $priority_reports = SAWService::getLaporanByPrioritas(5);
        } catch (\Exception $e) {
            $stats = [
                'total' => 0,
                'menunggu_verifikasi' => 0,
                'diverifikasi' => 0,
                'dalam_perbaikan' => 0,
                'selesai' => 0,
                'ditolak' => 0, // TAMBAHKAN INI
            ];

            $recent_reports = collect([]);
            $priority_reports = collect([]);
        }

        return view('admin.dashboard', compact('stats', 'recent_reports', 'priority_reports'));
    }

    public function reports(Request $request)
    {
        try {
            $query = Report::query();

            // Exclude Selesai dan Ditolak dari halaman ini
            $query->whereNotIn('status', ['Selesai', 'Ditolak']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_pelapor', 'LIKE', "%{$search}%")
                        ->orWhere('lokasi', 'LIKE', "%{$search}%")
                        ->orWhere('deskripsi', 'LIKE', "%{$search}%");
                });
            }

            $reports = $query->orderByRaw('COALESCE(prioritas, 999999) ASC')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

        } catch (\Exception $e) {
            $reports = collect([]);
        }

        return view('admin.reports', compact('reports'));
    }

    // TAMBAHKAN METHOD BARU UNTUK LAPORAN SELESAI
    public function laporanSelesai(Request $request)
    {
        try {
            $query = Report::where('status', 'Selesai');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_pelapor', 'LIKE', "%{$search}%")
                        ->orWhere('lokasi', 'LIKE', "%{$search}%")
                        ->orWhere('deskripsi', 'LIKE', "%{$search}%");
                });
            }

            $reports = $query->orderBy('tanggal_selesai_perbaikan', 'desc')
                ->orderBy('updated_at', 'desc')
                ->paginate(10);

        } catch (\Exception $e) {
            $reports = collect([]);
        }

        return view('admin.laporan-selesai', compact('reports'));
    }

    // TAMBAHKAN METHOD BARU UNTUK LAPORAN DITOLAK
    public function laporanDitolak(Request $request)
    {
        try {
            $query = Report::where('status', 'Ditolak');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_pelapor', 'LIKE', "%{$search}%")
                        ->orWhere('lokasi', 'LIKE', "%{$search}%")
                        ->orWhere('deskripsi', 'LIKE', "%{$search}%")
                        ->orWhere('alasan_penolakan', 'LIKE', "%{$search}%");
                });
            }

            $reports = $query->orderBy('updated_at', 'desc')->paginate(10);

        } catch (\Exception $e) {
            $reports = collect([]);
        }

        return view('admin.laporan-ditolak', compact('reports'));
    }

    public function show($id)
    {
        $report = Report::findOrFail($id);

        $saw_detail = null;
        if ($report->skor_saw) {
            $saw_detail = SAWService::getDetailPerhitungan($id);
        }

        return view('admin.show', compact('report', 'saw_detail'));
    }

    public function edit($id)
    {
        $report = Report::findOrFail($id);
        return view('admin.edit', compact('report'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Verifikasi,Diverifikasi,Dalam Perbaikan,Selesai,Ditolak',
            'catatan_admin' => 'nullable|string',
            'alasan_penolakan' => 'required_if:status,Ditolak|nullable|string',
            'estimasi_durasi' => 'nullable|integer|min:1',
            'tanggal_mulai_perbaikan' => 'nullable|date',
            'tanggal_selesai_perbaikan' => 'nullable|date|after:tanggal_mulai_perbaikan',
            'tingkat_kerusakan' => 'nullable|integer|min:1|max:5',
            'lokasi_strategis' => 'nullable|integer|min:1|max:5',
            'jumlah_pengguna' => 'nullable|integer|min:1|max:5',
            'kedekatan_fasum' => 'nullable|integer|min:1|max:5'
        ]);

        $report = Report::findOrFail($id);

        // Pastikan data yang di-update benar
        $updateData = [
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'estimasi_durasi' => $request->estimasi_durasi,
            'tanggal_mulai_perbaikan' => $request->tanggal_mulai_perbaikan,
            'tanggal_selesai_perbaikan' => $request->tanggal_selesai_perbaikan,
            'tingkat_kerusakan' => $request->tingkat_kerusakan ?? 0,
            'lokasi_strategis' => $request->lokasi_strategis ?? 0,
            'jumlah_pengguna' => $request->jumlah_pengguna ?? 0,
            'kedekatan_fasum' => $request->kedekatan_fasum ?? 0,
            'alasan_penolakan' => $request->status == 'Ditolak' ? $request->alasan_penolakan : null,
        ];

        $report->update($updateData);

        // Jika ditolak, hapus prioritas SAW
        if ($request->status == 'Ditolak') {
            $report->skor_saw = null;
            $report->prioritas = null;
            $report->save();
        }

        // Hitung ulang skor SAW jika kriteria diisi dan status bukan ditolak
        if (
            $request->status != 'Ditolak' &&
            $request->tingkat_kerusakan &&
            $request->lokasi_strategis &&
            $request->jumlah_pengguna &&
            $request->kedekatan_fasum
        ) {
            $report->hitungSkorSAW();
            SAWService::hitungSemuaPrioritas();

            return redirect()->route('admin.show', $report->id)
                ->with('success', 'Status laporan berhasil diupdate dan prioritas telah dihitung ulang');
        }

        $message = 'Status laporan berhasil diupdate';
        if ($request->status == 'Ditolak') {
            $message = 'Laporan berhasil ditolak dan telah dipindahkan ke halaman Laporan Ditolak';
        } elseif ($request->status == 'Selesai') {
            $message = 'Laporan berhasil diselesaikan dan telah dipindahkan ke halaman Laporan Selesai';
        }

        return redirect()->route('admin.show', $report->id)->with('success', $message);
    }
    public function prioritas()
    {
        try {
            $reports = SAWService::getLaporanByPrioritas();
        } catch (\Exception $e) {
            $reports = collect([]);
        }

        return view('admin.reports.prioritas', compact('reports'));
    }

    public function hitungUlangPrioritas()
    {
        try {
            SAWService::hitungSemuaPrioritas();
            return redirect()->back()->with('success', 'Prioritas berhasil dihitung ulang untuk semua laporan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghitung prioritas: ' . $e->getMessage());
        }
    }

    public function exportPrioritas()
    {
        try {
            $reports = SAWService::getLaporanByPrioritas();

            $filename = 'prioritas_perbaikan_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($reports) {
                $file = fopen('php://output', 'w');

                fputcsv($file, [
                    'Prioritas',
                    'ID Laporan',
                    'Lokasi',
                    'Status',
                    'Tingkat Kerusakan',
                    'Lokasi Strategis',
                    'Jumlah Pengguna',
                    'Kedekatan Fasum',
                    'Skor SAW',
                    'Tanggal Laporan'
                ]);

                foreach ($reports as $report) {
                    fputcsv($file, [
                        $report->prioritas,
                        $report->id,
                        $report->lokasi,
                        $report->status,
                        $report->tingkat_kerusakan_label,
                        $report->lokasi_strategis_label,
                        $report->jumlah_pengguna_label,
                        $report->kedekatan_fasum_label,
                        number_format($report->skor_saw, 4),
                        $report->created_at->format('d/m/Y H:i')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export data: ' . $e->getMessage());
        }
    }
     public function downloadPDF($id)
    {
        $report = Report::findOrFail($id);
        
        // Validasi: hanya laporan yang selesai yang bisa di-download
        if ($report->status != 'Selesai') {
            return redirect()->back()->with('error', 'Hanya laporan yang sudah selesai yang dapat diunduh dalam format PDF');
        }

        // Get SAW detail jika ada
        $saw_detail = null;
        if ($report->skor_saw) {
            $saw_detail = SAWService::getDetailPerhitungan($id);
        }

        // Generate PDF
        $pdf = Pdf::loadView('admin.pdf.report', [
            'report' => $report,
            'saw_detail' => $saw_detail
        ]);

        // Set paper size dan orientation
        $pdf->setPaper('A4', 'portrait');

        // Download dengan nama file
        $filename = 'Laporan_' . $report->id . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    // Method untuk preview PDF sebelum download
    public function previewPDF($id)
    {
        $report = Report::findOrFail($id);
        
        if ($report->status != 'Selesai') {
            return redirect()->back()->with('error', 'Hanya laporan yang sudah selesai yang dapat dilihat dalam format PDF');
        }

        $saw_detail = null;
        if ($report->skor_saw) {
            $saw_detail = SAWService::getDetailPerhitungan($id);
        }

        $pdf = Pdf::loadView('admin.pdf.report', [
            'report' => $report,
            'saw_detail' => $saw_detail
        ]);

        $pdf->setPaper('A4', 'portrait');

        // Stream untuk preview
        return $pdf->stream('Laporan_' . $report->id . '.pdf');
    }
}