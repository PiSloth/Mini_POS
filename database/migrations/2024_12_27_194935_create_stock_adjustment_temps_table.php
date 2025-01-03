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
        Schema::create('stock_adjustment_temps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_product_id')->constrained();
            $table->integer('quantity');
            $table->string('remark');
            $table->foreignId('user_id')->constrained();
            $table->boolean('is_stock_in')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustment_temps');
    }
};
