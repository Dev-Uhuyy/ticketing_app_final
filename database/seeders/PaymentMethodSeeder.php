<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Bank Transfer',
                'code' => 'bank_transfer',
                'description' => 'Transfer bank ke rekening website',
                'fee_percentage' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Kartu Kredit',
                'code' => 'credit_card',
                'description' => 'Pembayaran menggunakan kartu kredit',
                'fee_percentage' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'E-Wallet',
                'code' => 'ewallet',
                'description' => 'Pembayaran menggunakan dompet digital (GCash, Dana, dll)',
                'fee_percentage' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Cicilan',
                'code' => 'installment',
                'description' => 'Pembayaran dengan sistem cicilan',
                'fee_percentage' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}
