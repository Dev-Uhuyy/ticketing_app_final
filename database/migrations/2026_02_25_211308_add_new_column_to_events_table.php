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
        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', ['draft', 'scheduled', 'published', 'on_going', 'finished'])->default('draft');
            $table->timestamp('publish_at')->nullable();
            $table->dateTime('tanggal_waktu_selesai')->nullable();

            $table->text('deskripsi')->nullable()->change();
            $table->string('lokasi')->nullable()->change();
            $table->dateTime('tanggal_waktu_mulai')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['status', 'publish_at', 'tanggal_selesai']);

            $table->text('deskripsi')->nullable(false)->change();
            $table->string('lokasi')->nullable(false)->change();
            $table->dateTime('tanggal_waktu')->nullable(false)->change();
        });
    }
};
