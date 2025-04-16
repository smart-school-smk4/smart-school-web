@extends('layouts.dashboard')

@section('title', 'Smart School | Pengumuman')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-center text-2xl font-bold text-blue-600 mb-6">📢 Sistem Pengumuman</h2>

    <!-- Pilihan Jenis Pengumuman dalam Card -->
    <div id="cardContainer" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Card Pengumuman TTS -->
        <div id="ttsCard" class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg shadow-sm cursor-pointer hover:shadow-md transition-shadow border border-indigo-100 p-6">
            <h2 class="text-xl font-semibold text-indigo-700 mb-4">🎤 Pengumuman TTS</h2>
            <p class="text-gray-600">Kirim pengumuman dengan suara TTS ke ruangan yang dipilih.</p>
        </div>

        <!-- Card Pengumuman Biasa -->
        <div id="regularCard" class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg shadow-sm cursor-pointer hover:shadow-md transition-shadow border border-gray-100 p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">🎙️ Pengumuman Biasa</h2>
            <p class="text-gray-600">Kirim pengumuman langsung melalui mic ke ruangan yang dipilih.</p>
        </div>
    </div>

    <!-- Form untuk Pengumuman TTS -->
    <form id="ttsForm" class="bg-white shadow rounded-lg p-6 mb-6 hidden">
        @csrf
        <!-- Input Teks Pengumuman -->
        <div class="mb-4">
            <label for="text" class="block text-sm font-medium text-gray-700">Teks Pengumuman</label>
            <textarea id="text" name="text" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan teks pengumuman..." required></textarea>
        </div>

        <!-- Pilih Ruangan (Bisa Multiple) -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Pilih Ruangan</label>
            <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                @for ($i = 0; $i < 36; $i++)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="rooms[]" value="{{ $i }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-gray-700">Ruangan {{ $i }}</span>
                    </label>
                @endfor
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex space-x-4">
            <button type="button" id="backButtonTTS" class="w-full bg-gray-600 text-white p-2 rounded hover:bg-gray-700 transition duration-300">
                Kembali
            </button>
            <button type="submit" class="w-full bg-indigo-600 text-white p-2 rounded hover:bg-indigo-700 transition duration-300">
                Kirim Pengumuman TTS
            </button>
        </div>
    </form>

    <!-- Form untuk Pengumuman Biasa -->
    <form id="regularForm" class="bg-white shadow rounded-lg p-6 mb-6 hidden">
        @csrf
        <!-- Pilih Ruangan (Bisa Multiple) -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Pilih Ruangan</label>
            <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                @for ($i = 0; $i < 36; $i++)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="rooms[]" value="{{ $i }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-gray-700">Ruangan {{ $i }}</span>
                    </label>
                @endfor
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex space-x-4">
            <button type="button" id="backButtonRegular" class="w-full bg-gray-600 text-white p-2 rounded hover:bg-gray-700 transition duration-300">
                Kembali
            </button>
            <button type="button" id="startAnnouncement" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition duration-300">
                🎤 Mulai Pengumuman
            </button>
            <button type="button" id="endAnnouncement" class="w-full bg-red-600 text-white p-2 rounded hover:bg-red-700 transition duration-300 hidden">
                ⏹️ Selesai
            </button>
        </div>
    </form>

    <!-- Pesan Status -->
    <div id="statusMessage" class="mt-6 p-4 rounded-lg hidden">
        <p class="text-sm"></p>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script untuk mengelola tampilan form, validasi, dan mengirim data -->
<script>
    // Tampilkan form TTS saat card TTS diklik
    document.getElementById('ttsCard').addEventListener('click', function () {
        document.getElementById('cardContainer').classList.add('hidden');
        document.getElementById('ttsForm').classList.remove('hidden');
        document.getElementById('statusMessage').classList.add('hidden');
    });

    // Tampilkan form biasa saat card biasa diklik
    document.getElementById('regularCard').addEventListener('click', function () {
        document.getElementById('cardContainer').classList.add('hidden');
        document.getElementById('regularForm').classList.remove('hidden');
        document.getElementById('statusMessage').classList.add('hidden');
    });

    // Kembali ke pilihan card dari form TTS
    document.getElementById('backButtonTTS').addEventListener('click', function () {
        document.getElementById('ttsForm').classList.add('hidden');
        document.getElementById('cardContainer').classList.remove('hidden');
    });

    // Kembali ke pilihan card dari form biasa
    document.getElementById('backButtonRegular').addEventListener('click', function () {
        document.getElementById('regularForm').classList.add('hidden');
        document.getElementById('cardContainer').classList.remove('hidden');
    });

    // Validasi dan kirim form TTS
    document.getElementById('ttsForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // Validasi teks pengumuman
        const text = document.getElementById('text').value.trim();
        if (!text) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Teks pengumuman wajib diisi!',
            });
            return;
        }

        // Validasi ruangan
        const rooms = Array.from(document.querySelectorAll('#ttsForm input[name="rooms[]"]:checked'));
        if (rooms.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Pilih minimal satu ruangan!',
            });
            return;
        }

        // Kirim data ke Laravel menggunakan Axios
        const formData = new FormData(this);
        const statusMessage = document.getElementById('statusMessage');

        axios.post('/admin/pengumuman/send-tts', formData)
            .then(response => {
                // Tampilkan dialog berhasil
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Pengumuman TTS berhasil dikirim.',
                });

                // Reset form
                document.getElementById('ttsForm').reset(); // Reset semua input di form
                document.getElementById('ttsForm').classList.add('hidden'); // Sembunyikan form
                document.getElementById('cardContainer').classList.remove('hidden'); // Tampilkan kembali pilihan card
            })
            .catch(error => {
                // Tampilkan dialog gagal
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Gagal mengirim pengumuman TTS: ' + (error.response?.data?.message || 'Terjadi kesalahan.'),
                });
            });
    });

    // Mulai pengumuman biasa
    document.getElementById('startAnnouncement').addEventListener('click', function () {
        const rooms = Array.from(document.querySelectorAll('#regularForm input[name="rooms[]"]:checked'));
        if (rooms.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Pilih minimal satu ruangan!',
            });
            return;
        }

        // Tampilkan dialog untuk menyuruh user menyalakan mikrofon
        Swal.fire({
            icon: 'info',
            title: 'Aktifkan Mikrofon',
            text: 'Silakan pastikan mikrofon sudah aktif dan siap digunakan.',
            confirmButtonText: 'Mikrofon Sudah Aktif',
        }).then((result) => {
            if (result.isConfirmed) {
                // Aktifkan pengumuman di ruangan yang dipilih
                rooms.forEach(room => {
                    activateAnnouncement(room.value);
                });

                // Tampilkan tombol "Selesai"
                document.getElementById('endAnnouncement').classList.remove('hidden');
                document.getElementById('startAnnouncement').classList.add('hidden');

                Swal.fire({
                    icon: 'success',
                    title: 'Pengumuman Dimulai',
                    text: 'Pengumuman aktif di ruangan yang dipilih.',
                });
            }
        });
    });

    // Selesai pengumuman biasa
    document.getElementById('endAnnouncement').addEventListener('click', function () {
        const rooms = Array.from(document.querySelectorAll('#regularForm input[name="rooms[]"]:checked'));
        if (rooms.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Tidak ada ruangan yang dipilih!',
            });
            return;
        }

        // Matikan pengumuman di ruangan yang dipilih
        rooms.forEach(room => {
            deactivateAnnouncement(room.value);
        });

        // Reset checkbox yang terpilih
        rooms.forEach(room => {
            room.checked = false;
        });

        // Sembunyikan tombol "Selesai"
        document.getElementById('endAnnouncement').classList.add('hidden');
        document.getElementById('startAnnouncement').classList.remove('hidden');

        Swal.fire({
            icon: 'success',
            title: 'Pengumuman Selesai',
            text: 'Pengumuman telah dimatikan di ruangan yang dipilih.',
        });
    });

    // Fungsi untuk mengaktifkan pengumuman di ruangan
    function activateAnnouncement(room) {
        console.log(`Pengumuman diaktifkan di Ruangan ${room}.`);
        // Kirim perintah ke sistem untuk mengaktifkan pengumuman di ruangan tertentu
        // Contoh: axios.post('/api/activate-announcement', { room: room });
    }

    // Fungsi untuk mematikan pengumuman di ruangan
    function deactivateAnnouncement(room) {
        console.log(`Pengumuman dimatikan di Ruangan ${room}.`);
        // Kirim perintah ke sistem untuk mematikan pengumuman di ruangan tertentu
        // Contoh: axios.post('/api/deactivate-announcement', { room: room });
    }
</script>
@endsection