<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::query()->insert([
            [
                'title'         => 'Bootcamp Static Analysis',
                'description'   => 'Pengenalan SonarQube dan analisis kode statis.',
                'activity_date' => '2026-09-18',
                'category'      => 'Bootcamp',
                'status'        => 'Done',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'title'         => 'Rapat Koordinasi Job Proyek',
                'description'   => 'Evaluasi pembagian tugas proyek akhir semester.',
                'activity_date' => '2026-09-20',
                'category'      => 'Rapat',
                'status'        => 'Done',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'title'         => 'Seminar Web Quality',
                'description'   => 'Pengenalan maintainability dan testing.',
                'activity_date' => '2026-10-12',
                'category'      => 'Seminar',
                'status'        => 'Ongoing',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'title'         => 'Pelatihan Laravel Lanjutan',
                'description'   => 'Implementasi service layer dan refactor controller.',
                'activity_date' => '2026-10-15',
                'category'      => 'Pelatihan',
                'status'        => 'Planned',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'title'         => 'Workshop Git Dasar',
                'description'   => 'Latihan kolaborasi repository.',
                'activity_date' => '2026-10-05',
                'category'      => 'Workshop',
                'status'        => 'Planned',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}