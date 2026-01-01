<?php
// app/Services/WhatsAppService.php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('whatsapp.api_url', 'http://localhost:3000');
        $this->apiKey = config('whatsapp.api_key', '');
    }

    /**
     * Send WhatsApp message
     */
    public function sendMessage($phone, $message)
    {
        try {
            // Clean phone number (remove +, spaces, etc)
            $cleanPhone = $this->cleanPhoneNumber($phone);
            
            $response = Http::timeout(30)->post("{$this->apiUrl}/api/sendText", [
                'phone' => $cleanPhone,
                'message' => $message
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp sent successfully to {$cleanPhone}");
                return [
                    'success' => true,
                    'message' => 'Pesan berhasil dikirim',
                    'response' => $response->json()
                ];
            } else {
                Log::error("WhatsApp failed to {$cleanPhone}: " . $response->body());
                return [
                    'success' => false,
                    'message' => 'Gagal mengirim pesan: ' . $response->body()
                ];
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp exception: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send status update notification
     */
    public function sendStatusUpdate(Report $report)
    {
        if (!$report->reporter_phone) {
            return ['success' => false, 'message' => 'Nomor telepon tidak tersedia'];
        }

        $message = $this->generateStatusMessage($report);
        return $this->sendMessage($report->reporter_phone, $message);
    }

    /**
     * Generate status message based on report status
     */
    protected function generateStatusMessage(Report $report)
    {
        $appName = config('app.name', 'Laporanku');
        $baseMessage = "[{$appName} – Sidoarjo]\n\n";

        switch ($report->status) {
            case Report::STATUS_VERIFIED:
                return $baseMessage . 
                    "✅ Laporan Anda telah diverifikasi dan akan segera ditindaklanjuti.\n\n" .
                    "📍 Lokasi: {$report->location}\n" .
                    "📋 Perihal: {$report->title}\n\n" .
                    "Terima kasih atas laporan Anda.";

            case Report::STATUS_IN_PROGRESS:
                $estimasi = $report->estimated_duration_days 
                    ? "{$report->estimated_duration_days} hari kerja" 
                    : "segera";
                    
                return $baseMessage . 
                    "🔧 Laporan Anda sedang dalam proses perbaikan.\n\n" .
                    "📍 Lokasi: {$report->location}\n" .
                    "📅 Mulai perbaikan: " . $report->start_repair_date->format('d M Y') . "\n" .
                    "⏰ Estimasi selesai: {$estimasi}\n\n" .
                    "Tim kami sedang bekerja untuk menyelesaikan masalah ini.";

            case Report::STATUS_COMPLETED:
                $duration = $report->actual_duration 
                    ? " dalam {$report->actual_duration} hari" 
                    : "";
                    
                return $baseMessage . 
                    "✅ Laporan Anda telah selesai ditangani pada " . 
                    $report->completion_date->format('d M Y') . "{$duration}.\n\n" .
                    "📍 Lokasi: {$report->location}\n" .
                    "📋 Perihal: {$report->title}\n\n" .
                    "Terima kasih atas partisipasi Anda dalam membangun Sidoarjo yang lebih baik! 🙏";

            default:
                return $baseMessage . 
                    "📝 Status laporan Anda telah diperbarui.\n\n" .
                    "📍 Lokasi: {$report->location}\n" .
                    "📊 Status: {$report->status_label}\n\n" .
                    "Terima kasih.";
        }
    }

    /**
     * Clean phone number format
     */
    protected function cleanPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert to international format for Indonesia
        if (substr($clean, 0, 1) === '0') {
            $clean = '62' . substr($clean, 1);
        } elseif (substr($clean, 0, 2) !== '62') {
            $clean = '62' . $clean;
        }
        
        return $clean . '@c.us';
    }

    /**
     * Test WhatsApp connection
     */
    public function testConnection()
    {
        try {
            $response = Http::timeout(10)->get("{$this->apiUrl}/api/getConnectionState");
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'status' => $data['state'] ?? 'unknown',
                    'message' => 'Koneksi WhatsApp tersedia'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Tidak dapat terhubung ke WhatsApp service'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}