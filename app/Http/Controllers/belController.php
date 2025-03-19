<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalBel;

class BelController extends Controller
{
    // Menampilkan halaman utama jadwal bel dengan pagination
    public function index()
    {
        $jadwalBels = JadwalBel::orderBy('waktu')->paginate(10);
        return view('admin.bel.bel', compact('jadwalBels'));
    }

    // Menampilkan form tambah jadwal bel
    public function create()
    {
        return view('admin.bel.create');
    }

    // Menyimpan jadwal bel baru dengan validasi ketat
    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'waktu' => 'required|date_format:H:i', // Menggunakan format waktu
            'mp3_file' => 'required|integer|min:1|max:9999',
        ]);

        JadwalBel::create($request->all());

        return redirect()->route('bel.index')->with([
            'success' => 'Jadwal bel berhasil ditambahkan!',
            'type' => 'success'
        ]);
    }

    // Menampilkan detail jadwal bel
    public function show($id)
    {
        $jadwalBel = JadwalBel::findOrFail($id);
        return view('admin.bel.show', compact('jadwalBel'));
    }

    // Menampilkan form edit jadwal bel
    public function edit($id)
    {
        $jadwalBel = JadwalBel::findOrFail($id);
        return view('admin.bel.edit', compact('jadwalBel'));
    }

    // Memperbarui jadwal bel
    public function update(Request $request, $id)
    {
        $request->validate([
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'waktu' => 'required|date_format:H:i',
            'mp3_file' => 'required|integer|min:1|max:9999',
        ]);

        $jadwalBel = JadwalBel::findOrFail($id);
        $jadwalBel->update($request->all());

        return redirect()->route('bel.index')->with([
            'success' => 'Jadwal bel berhasil diperbarui!',
            'type' => 'info'
        ]);
    }

    // Menghapus jadwal bel
    public function destroy($id)
    {
        $jadwalBel = JadwalBel::findOrFail($id);
        $jadwalBel->delete();

        return redirect()->route('bel.index')->with([
            'success' => 'Jadwal bel berhasil dihapus!',
            'type' => 'danger'
        ]);
    }

    // API untuk mengambil jadwal bel berdasarkan hari ini
    public function getSchedule()
    {
        $hariIni = now()->format('l'); // Mengambil nama hari dalam bahasa Inggris

        // Konversi ke format Indonesia (sesuai validasi)
        $hariIndonesia = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu'
        ];

        $hariSekarang = $hariIndonesia[$hariIni]; // Konversi ke nama hari Indonesia

        // Ambil hanya jadwal untuk hari ini
        $jadwalHariIni = JadwalBel::where('hari', $hariSekarang)
            ->orderBy('waktu')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $jadwalHariIni
        ], 200);
    }

}
