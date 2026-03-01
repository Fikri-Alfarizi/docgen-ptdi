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
        Schema::table('users', function (Blueprint $table) {
            $table->index('name');
            $table->index('nik');
        });

        Schema::table('templates', function (Blueprint $table) {
            $table->index('nama_template');
            $table->index('nomor');
            $table->index('org');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->index('jenis_dokumen');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['nik']);
        });

        Schema::table('templates', function (Blueprint $table) {
            $table->dropIndex(['nama_template']);
            $table->dropIndex(['nomor']);
            $table->dropIndex(['org']);
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['jenis_dokumen']);
            $table->dropIndex(['created_at']);
        });
    }
};
