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
        Schema::create('therapist_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('therapist_id');
            $table->string('reason');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status',['pending','approved','rejected']);
            // $table->softDeletesIfNotExists
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('therapist_leaves');
    }
};
