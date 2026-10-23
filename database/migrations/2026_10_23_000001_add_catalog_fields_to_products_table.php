<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('compare_price', 12, 2)->nullable()->after('price');
            $table->unsignedInteger('stock')->default(0)->after('compare_price');
            $table->json('sizes')->nullable()->after('stock');
            $table->json('colors')->nullable()->after('sizes');
            $table->string('badge', 20)->nullable()->after('colors');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->unsignedInteger('wa_clicks')->default(0)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['compare_price', 'stock', 'sizes', 'colors', 'badge', 'is_featured', 'wa_clicks']);
        });
    }
};
