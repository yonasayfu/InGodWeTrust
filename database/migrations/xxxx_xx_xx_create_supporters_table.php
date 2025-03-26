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
        Schema::create('supporters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('age');
            $table->string('gender');
            $table->text('address');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('support_type');
            $table->decimal('contribution_amount', 10, 2);
            $table->integer('commit_duration');
            $table->date('start_date');
            $table->text('bank_details');
            $table->text('testimonial_content')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supporters');
    }
};