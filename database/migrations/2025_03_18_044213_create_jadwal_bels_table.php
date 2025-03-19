<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('jadwal_bels', function (Blueprint $table) {
            $table->id();
            $table->string('hari', 10); // Menyimpan hari (Senin, Selasa, dll.), dibatasi 10 karakter
            $table->time('waktu'); // Menyimpan waktu dalam format HH:MM:SS
            $table->unsignedInteger('mp3_file'); // Menyimpan nomor file MP3 (0001, 0002, dll.), tidak boleh negatif
            $table->timestamps();
            $table->softDeletes(); // Menambahkan kolom deleted_at untuk SoftDeletes

            // Pastikan tidak ada jadwal duplikat untuk hari & waktu yang sama
            $table->unique(['hari', 'waktu']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_bels');
    }
};
