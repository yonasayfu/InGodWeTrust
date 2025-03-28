<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetDonationsTable extends Migration
{
    public function up()
    {
        Schema::create('target_donations', function (Blueprint $table) {
            $table->id();
            $table->string('period');
            $table->decimal('target_amount', 15, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->softDeletes(); // Add soft deletes
        });
    }

    public function down()
    {
        Schema::dropIfExists('target_donations');
    }
}
