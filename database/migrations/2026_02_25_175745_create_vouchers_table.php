<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('deskripsi')->nullable();
            $table->decimal('diskon', 10, 2);
            $table->enum('tipe_diskon', ['fixed', 'percent']);
            $table->integer('penggunaan_maksimal')->nullable();
            $table->integer('jumlah_digunakan')->default(0);
            $table->dateTime('tanggal_kadaluarsa')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
