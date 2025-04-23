<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jurusan::insert([
            [
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
            ],
            [
                'nama_jurusan' => 'Teknik Informatika',
            ],
        ]);
    }
}
