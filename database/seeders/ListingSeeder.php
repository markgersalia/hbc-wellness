<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListingSeeder extends Seeder
{
   
      /**
     * Run the database seeds.
     */
    public function run(): void
{
           $typeTitles = [ 
            'service' => ['Premium Cleaning Service', 'Car Wash Package', 'Massage Service'],
            'event' => ['Corporate Event Hall', 'Wedding Venue', 'Birthday Party Space'],
            'apartment' => ['1BR City Apartment', 'Modern Loft Apartment', 'Penthouse Suite'],
            'house' => ['Family Home Rental', 'Modern Smart House', 'Suburban Home'], 
        ];

    // Add medical into types list
    $types = array_keys($typeTitles);

    for ($i = 1; $i <= 20; $i++) {

        // Random type
        $type = $types[array_rand($types)];
        // Title depends on type
        $title = $typeTitles[$type][array_rand($typeTitles[$type])];

        DB::table('listings')->insert([
            // 'images' => 'https://via.placeholder.com/400x300?text=' . urlencode($title),
            'title' => $title,
            'description' => 'This is a description for ' . strtolower($title),
            'type' => $type,
            'price' => rand(500, 5000),
            'available_from' => Carbon::now()->addDays(rand(0, 30)),
            'available_to' => Carbon::now()->addDays(rand(31, 60)),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

}
