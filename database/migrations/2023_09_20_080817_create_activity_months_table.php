<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('activity_months', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('learning_activity_id');
            $table->text('activities')->nullable();
            $table->string('month');
            $table->string('start_date');
            $table->string('end_date');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('learning_activity_id')->references('id')->on('learning_activities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_months');
    }
};
