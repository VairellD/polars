<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Photography',
                'description' => 'Fotografi dan gambar visual',
                'icon' => 'bi-camera',
                'color' => '#28a745',
                'sort_order' => 1,
            ],
            [
                'name' => 'Video Production',
                'description' => 'Video, film, dan konten multimedia',
                'icon' => 'bi-play-circle',
                'color' => '#dc3545',
                'sort_order' => 2,
            ],
            [
                'name' => 'Audio Production',
                'description' => 'Musik, podcast, dan audio',
                'icon' => 'bi-music-note-beamed',
                'color' => '#ff6b35',
                'sort_order' => 3,
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'User Interface dan User Experience',
                'icon' => 'bi-palette',
                'color' => '#6f42c1',
                'sort_order' => 4,
            ],
            [
                'name' => 'Animation',
                'description' => 'Animasi 2D dan 3D',
                'icon' => 'bi-play',
                'color' => '#fd7e14',
                'sort_order' => 5,
            ],
            [
                'name' => 'Virtual Reality',
                'description' => 'VR dan pengalaman immersive',
                'icon' => 'bi-headset-vr',
                'color' => '#20c997',
                'sort_order' => 6,
            ],
            [
                'name' => 'Augmented Reality',
                'description' => 'AR dan mixed reality',
                'icon' => 'bi-phone',
                'color' => '#17a2b8',
                'sort_order' => 7,
            ],
            [
                'name' => 'Game Development',
                'description' => 'Pengembangan game dan interactive media',
                'icon' => 'bi-controller',
                'color' => '#6610f2',
                'sort_order' => 8,
            ],
            [
                'name' => 'Tugas Akhir',
                'description' => 'Final project dan thesis',
                'icon' => 'bi-mortarboard',
                'color' => '#e83e8c',
                'sort_order' => 9,
            ],
            [
                'name' => 'Tutorial',
                'description' => 'Tutorial dan pembelajaran',
                'icon' => 'bi-book',
                'color' => '#ffc107',
                'sort_order' => 10,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

// Jalankan dengan:
// php artisan make:seeder CategorySeeder
// Copy code ini lalu: php artisan db:seed --class=CategorySeeder
