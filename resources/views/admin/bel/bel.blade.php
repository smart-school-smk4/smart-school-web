@extends('layouts.dashboard')

@section('title', 'Smart School | Dashboard')

@section('content')
<div class="container mt-4">
    <h2 class="text-center text-primary fw-bold">📢 Sistem Bel Sekolah</h2>

    <!-- STATUS SISTEM -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow border-0 text-center p-3">
                <h5 class="text-secondary">Status RTC</h5>
                <span id="rtc-status" class="badge bg-warning p-2">⏳ Memeriksa...</span>
                <h6 class="mt-2">Waktu Saat Ini: <span id="rtc-time-display" class="fw-bold text-primary">--:--:--</span></h6>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow border-0 text-center p-3">
                <h5 class="text-secondary">Status DFPlayer</h5>
                <span id="dfplayer-status" class="badge bg-warning p-2">⏳ Memeriksa...</span>
            </div>
        </div>
    </div>

    <!-- KONFIGURASI RTC -->
    <div class="card mt-4 shadow border-0 p-3">
        <h5 class="fw-bold text-primary">⏰ Konfigurasi RTC</h5>
        <div class="row">
            <div class="col-md-4">
                <input type="date" class="form-control" id="rtc-date">
            </div>
            <div class="col-md-4">
                <input type="time" class="form-control" id="rtc-time">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100" onclick="updateRTC()">🔄 Perbarui RTC</button>
            </div>
        </div>
    </div>

    <!-- KONFIGURASI JADWAL -->
    <div class="card mt-4 shadow border-0 p-3">
        <h5 class="fw-bold text-info">📅 Konfigurasi Jadwal</h5>
        <table class="table text-center">
            <thead class="table-light">
                <tr>
                    <th>Hari</th>
                    <th>Waktu (HH:MM:SS)</th>
                    <th>File MP3</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="schedule-list">
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-3">
                <select class="form-control" id="schedule-day">
                    <option>Senin</option>
                    <option>Selasa</option>
                    <option>Rabu</option>
                    <option>Kamis</option>
                    <option>Jumat</option>
                    <option>Sabtu</option>
                    <option>Minggu</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="time" step="1" class="form-control" id="schedule-time">
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" placeholder="ID File (0001-9999)" id="schedule-mp3" min="1" max="9999">
            </div>
            <div class="col-md-3">
                <button class="btn btn-success w-100" onclick="addSchedule()">➕ Tambah</button>
            </div>
        </div>
    </div>

    <!-- KONTROL BEL MANUAL -->
    <div class="card mt-4 shadow border-0 text-center p-3 clickable-card" onclick="ringBell()">
        <h5 class="fw-bold text-danger">🔔 Kontrol Bel Manual</h5>
        <button class="btn btn-lg btn-warning">🔊 Bunyi Bel</button>
    </div>
</div>

<style>
    .clickable-card {
        transition: all 0.3s ease-in-out;
        cursor: pointer;
    }
    .clickable-card:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
</style>

<script>
    function fetchSystemStatus() {
        fetch('/admin/bel')
            .then(response => response.json())
            .then(data => {
                document.getElementById('rtc-status').textContent = data.rtc ? '✅ Terhubung' : '❌ Tidak Terhubung';
                document.getElementById('dfplayer-status').textContent = data.dfplayer ? '✅ Terhubung' : '❌ Tidak Terhubung';
                if (data.rtc_time) {
                    document.getElementById('rtc-time-display').textContent = data.rtc_time;
                }
            });
    }

    function addSchedule() {
        let day = document.getElementById('schedule-day').value;
        let time = document.getElementById('schedule-time').value;
        let mp3 = document.getElementById('schedule-mp3').value;
        if (!time || !mp3) {
            alert("Harap masukkan detail jadwal secara lengkap!");
            return;
        }
        fetch('/admin/bel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ day: day, time: time, mp3: mp3 }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Jadwal berhasil ditambahkan!');
                location.reload();
            } else {
                alert('Gagal menambah jadwal');
            }
        })
        .catch(error => alert('Terjadi kesalahan'));
    }

    function ringBell() {
        fetch('/admin/bel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ command: 'play', payload: 'mp3-001' }),
        })
        .then(response => response.json())
        .then(() => alert('Bel berbunyi!'))
        .catch(() => alert('Gagal mengirim perintah'));
    }

    fetchSystemStatus();
    setInterval(fetchSystemStatus, 5000);
</script>
@endsection
