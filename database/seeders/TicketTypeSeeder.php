<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ticketTypes = [
            [
                'name' => 'premium',
                'description' => 'Tiket dengan akses eksklusif dan fasilitas premium.',
            ],
            [
                'name' => 'regular',
                'description' => 'Tiket standar dengan akses umum.',
            ],
            [
                'name' => 'early_bird',
                'description' => 'Tiket dengan harga diskon untuk pembelian awal.',
            ],
        ];

        foreach ($ticketTypes as $type) {
            \App\Models\TicketType::create($type);
        }
    }
}
