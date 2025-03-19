<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalBel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadwal_bels'; // Pastikan sesuai dengan database

    protected $fillable = [
        'hari',      // Nama hari (Senin, Selasa, Rabu, dll.)
        'jam',       // Jam bel berbunyi
        'menit',     // Menit bel berbunyi
        'file_number' // Nomor file MP3 (misal: "0001", "0002")
    ];

    protected $dates = ['deleted_at']; // Untuk SoftDeletes

    protected $casts = [
        'jam' => 'integer',
        'menit' => 'integer',
        'file_number' => 'string', // Ubah ke string jika format MP3 "0001", "0002"
    ];

    // Mutator untuk memastikan nama hari selalu format Title Case (Senin, Selasa, dll.)
    public function setHariAttribute($value)
    {
        $this->attributes['hari'] = ucfirst(strtolower($value));
    }

    // Accessor untuk menampilkan waktu dalam format "HH:MM"
    public function getWaktuAttribute()
    {
        return sprintf('%02d:%02d', $this->jam, $this->menit);
    }
}
