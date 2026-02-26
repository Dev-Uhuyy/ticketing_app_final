<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Rename kolom lama ke nama baru
            $table->renameColumn('tanggal_waktu', 'tanggal_waktu_mulai');
        });

        Schema::table('events', function (Blueprint $table) {
            // Jadikan nullable
            $table->dateTime('tanggal_waktu_mulai')->nullable()->change();

            // Tambah kolom baru
            $table->dateTime('tanggal_waktu_selesai')->nullable()->after('tanggal_waktu_mulai');
            $table->enum('status', ['draft', 'scheduled', 'published', 'on_going', 'finished'])->default('draft')->after('tanggal_waktu_selesai');
            $table->timestamp('publish_at')->nullable()->after('status');

            // Jadikan nullable
            $table->text('deskripsi')->nullable()->change();
            $table->string('lokasi')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['tanggal_waktu_selesai', 'status', 'publish_at']);

            $table->text('deskripsi')->nullable(false)->change();
            $table->string('lokasi')->nullable(false)->change();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('tanggal_waktu_mulai', 'tanggal_waktu');
        });
    }
};