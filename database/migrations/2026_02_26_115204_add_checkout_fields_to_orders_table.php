<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('nama_pemesan')->after('total_harga');
            $table->string('email_pemesan')->after('nama_pemesan');
            $table->string('no_telp')->after('email_pemesan');
            $table->foreignId('voucher_id')->nullable()->after('no_telp')->constrained()->onDelete('set null');
            $table->decimal('diskon_amount', 10, 2)->default(0)->after('voucher_id');
            $table->foreignId('payment_method_id')->nullable()->after('diskon_amount')->constrained()->onDelete('set null');
            $table->decimal('total_bayar', 10, 2)->after('payment_method_id');
            $table->string('status_pembayaran')->default('pending')->after('total_bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn([
                'nama_pemesan',
                'email_pemesan',
                'no_telp',
                'voucher_id',
                'diskon_amount',
                'payment_method_id',
                'total_bayar',
                'status_pembayaran'
            ]);
        });
    }
};
