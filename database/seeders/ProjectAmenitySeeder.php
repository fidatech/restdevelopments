<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectAmenity;

class ProjectAmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            [
                'image' => 'assets/img/aminities/aminities1-1.png',
                'icon'  => 'assets/img/icon/aminities-icon1-1.svg',
                'order' => 1,
                'title' => [
                    'en' => 'Car Parking',
                    'fa' => 'Car Parking',
                ],
            ],
            [
                'image' => 'assets/img/aminities/aminities1-2.png',
                'icon'  => 'assets/img/icon/aminities-icon1-2.svg',
                'order' => 2,
                'title' => [
                    'en' => 'Fitness Center',
                    'fa' => 'Fitness Center',
                ],
            ],
            [
                'image' => 'assets/img/aminities/aminities1-3.png',
                'icon'  => 'assets/img/icon/aminities-icon1-3.svg',
                'order' => 3,
                'title' => [
                    'en' => 'Rooftop Garden',
                    'fa' => 'Rooftop Garden',
                ],
            ],
            [
                'image' => 'assets/img/aminities/aminities1-4.png',
                'icon'  => 'assets/img/icon/aminities-icon1-4.svg',
                'order' => 4,
                'title' => [
                    'en' => 'Indoor Pool',
                    'fa' => 'Indoor Pool',
                ],
            ],
            [
                'image' => 'assets/img/aminities/aminities1-5.png',
                'icon'  => 'assets/img/icon/aminities-icon1-5.svg',
                'order' => 5,
                'title' => [
                    'en' => 'Pet Friendly',
                    'fa' => 'Pet Friendly',
                ],
            ],
            [
                'image' => 'assets/img/aminities/aminities1-6.png',
                'icon'  => 'assets/img/icon/aminities-icon1-6.svg',
                'order' => 6,
                'title' => [
                    'en' => 'Playground',
                    'fa' => 'Playground',
                ],
            ],
        ];

        foreach ($amenities as $item) {
            $a = ProjectAmenity::create([
                'image'  => $item['image'],
                'icon'   => $item['icon'],
                'order'  => $item['order'],
                'status' => true,
            ]);

            foreach ($item['title'] as $locale => $title) {
                $a->translations()->create([
                    'locale' => $locale,
                    'title'  => $title,
                ]);
            }
        }
    }
}
