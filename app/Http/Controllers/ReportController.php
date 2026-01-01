<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'alamat_lengkap' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['status'] = 'Menunggu Verifikasi'; // Set default status

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('reports', 'public');
        }

        Report::create($data);

        return redirect()->route('reports.success')->with('success', 'Laporan berhasil dikirim!');
    }

    public function success()
    {
        return view('reports.success');
    }

    public function check()
    {
        return view('reports.check');
    }

    public function status(Request $request)
    {
        $request->validate([
            'nomor_hp' => 'required|string'
        ]);

        $reports = Report::where('nomor_hp', $request->nomor_hp)
                        ->orderBy('created_at', 'desc')
                        ->get();

        if ($reports->isEmpty()) {
            return redirect()->back()->with('error', 'Laporan tidak ditemukan dengan nomor HP tersebut.');
        }

        return view('reports.status', compact('reports'));
    }

    // Method untuk mendapatkan laporan dengan GPS (API)
    public function getReportsWithGps()
    {
        $reports = Report::whereNotNull('latitude')
                        ->whereNotNull('longitude')
                        ->where('status', '!=', 'Ditolak')
                        ->get();

        return response()->json($reports);
    }
}