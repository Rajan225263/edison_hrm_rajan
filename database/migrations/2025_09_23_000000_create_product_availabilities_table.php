<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->softDeletes(); // 🟢 Soft delete column (deleted_at)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_availabilities');
    }
};
