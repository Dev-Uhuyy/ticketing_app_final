<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
  public function run(): void
  {
    $names = ['Konser', 'Running', 'Seminar'];

    foreach ($names as $name) {
      Kategori::firstOrCreate(['nama' => $name]);
    }
  }
}
