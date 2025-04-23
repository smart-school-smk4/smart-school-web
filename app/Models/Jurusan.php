<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';

    protected $fillable = ['nama_jurusan'];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_jurusan');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_jurusan');
    }
}