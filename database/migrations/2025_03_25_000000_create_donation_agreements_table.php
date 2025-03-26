<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationAgreementsTable extends Migration
{
    public function up()
    {
        Schema::create('donation_agreements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supporter_id');
            $table->unsignedBigInteger('bank_id');
            $table->string('donation_type');
            $table->decimal('donation_amount', 10, 2);
            $table->string('recurring_interval')->nullable();
            $table->string('signed_agreement_pdf');
            $table->string('summary_pdf');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('supporter_id')->references('id')->on('supporters')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('bank_forms')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_agreements');
    }
}