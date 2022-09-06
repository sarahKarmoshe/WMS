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
        Schema::create('expiry_dates_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expiry_dates_id')->constrained('expiry_dates')->cascadeOnDelete();
            $table->foreignId('imports_id')->constrained('imports')->cascadeOnDelete();
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
        Schema::dropIfExists('expiry_dates_imports');
    }
};
