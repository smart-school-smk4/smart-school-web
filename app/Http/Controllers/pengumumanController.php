<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    /**
     * Menampilkan halaman pengumuman.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.bel.pengumuman');
    }

    /**
     * Mengirim pengumuman TTS ke ESP32.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTTS(Request $request)
    {
        // Validasi input
        $request->validate([
            'text' => 'required|string', // Teks pengumuman
            'rooms' => 'required|array', // Array ruangan yang dipilih
            'rooms.*' => 'integer|min:0|max:35', // Setiap ruangan harus antara 0-35
        ]);

        // Ambil VoiceRSS API Key dari .env
        $voiceRssApiKey = env('VOICERSS_API_KEY');
        if (!$voiceRssApiKey) {
            return response()->json(['status' => 'error', 'message' => 'VoiceRSS API Key tidak ditemukan.'], 500);
        }

        // Buat URL VoiceRSS
        $voiceRssUrl = "http://api.voicerss.org/?key={$voiceRssApiKey}&hl=en-us&src=" . urlencode($request->input('text'));

        // Unduh file audio dari VoiceRSS menggunakan GuzzleHttp
        $client = new Client();
        try {
            $response = $client->get($voiceRssUrl);
            $audioContent = $response->getBody()->getContents();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengunduh audio dari VoiceRSS.'], 500);
        }

        // Simpan file audio sementara di storage Laravel
        $fileName = 'announcement_' . time() . '.mp3'; // Nama file unik
        Storage::disk('public')->put('audio/' . $fileName, $audioContent);
        $audioUrl = Storage::url('audio/' . $fileName);

        // Kirim URL file audio ke ESP32
        $esp32Url = "http://esp32-ip-address/announcement"; // Ganti dengan IP ESP32
        try {
            $response = $client->post($esp32Url, [
                'json' => [
                    'audio_url' => $audioUrl, // URL file audio
                    'rooms' => $request->input('rooms'), // Array ruangan yang dipilih
                ],
            ]);

            // Hapus file audio setelah dikirim
            Storage::disk('public')->delete('audio/' . $fileName);

            // Berikan respons ke client
            if ($response->getStatusCode() == 200) {
                return response()->json(['status' => 'success', 'message' => 'Pengumuman terkirim.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Gagal mengirim pengumuman ke ESP32.'], 500);
            }
        } catch (\Exception $e) {
            // Hapus file audio jika gagal
            Storage::disk('public')->delete('audio/' . $fileName);
            return response()->json(['status' => 'error', 'message' => 'Gagal mengirim pengumuman ke ESP32: ' . $e->getMessage()], 500);
        }
    }
}