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
        Schema::create('details', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->string('version_id');
            $table->string('type');
            $table->string('value');
            $table->string('brand');
            $table->string('model');
            $table->string('reference');
            $table->string('fuel');
            $table->string('fuel_type');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('version_id')->references('id')->on('versions');
            $table->primary(['id', 'version_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details');
    }
};
