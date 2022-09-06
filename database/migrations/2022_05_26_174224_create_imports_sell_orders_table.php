<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imports_sell_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sell_order_id')->constrained('sell_orders')->cascadeOnDelete();
            $table->foreignId('import_id')->constrained('imports')->cascadeOnDelete();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imports_sell_orders');
    }
};
