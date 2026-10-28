<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            // Gambar potret khusus layar HP; kosong = pakai gambar desktop
            $table->string('image_mobile_path')->nullable()->after('image_path');
            $table->boolean('hide_text_mobile')->default(false)->after('image_mobile_path');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['image_mobile_path', 'hide_text_mobile']);
        });
    }
};
