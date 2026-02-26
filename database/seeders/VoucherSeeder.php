<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'DISKON2025',
                'deskripsi' => 'Diskon 20% untuk semua tiket',
                'diskon' => 20,
                'tipe_diskon' => 'percent',
                'penggunaan_maksimal' => 100,
                'jumlah_digunakan' => 0,
                'tanggal_kadaluarsa' => now()->addMonths(1),
                'aktif' => true,
            ],
            [
                'code' => 'PROMO50K',
                'deskripsi' => 'Potongan harga tetap Rp 50.000',
                'diskon' => 50000,
                'tipe_diskon' => 'fixed',
                'penggunaan_maksimal' => 50,
                'jumlah_digunakan' => 0,
                'tanggal_kadaluarsa' => now()->addMonths(2),
                'aktif' => true,
            ],
            [
                'code' => 'WELCOME15',
                'deskripsi' => 'Diskon 15% untuk member baru',
                'diskon' => 15,
                'tipe_diskon' => 'percent',
                'penggunaan_maksimal' => null,
                'jumlah_digunakan' => 0,
                'tanggal_kadaluarsa' => null,
                'aktif' => true,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}

