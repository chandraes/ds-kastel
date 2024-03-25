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
        Schema::create('kas_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects');
            $table->string('uraian');
            $table->boolean('jenis');
            $table->bigInteger('nominal');
            $table->bigInteger('sisa');
            $table->string('no_rek')->nullable();
            $table->string('nama_rek')->nullable();
            $table->string('bank')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_projects');
    }
};
