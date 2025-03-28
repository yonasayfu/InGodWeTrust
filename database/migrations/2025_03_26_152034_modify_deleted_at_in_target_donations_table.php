<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('target_donations', function (Blueprint $table) {
            if (Schema::hasColumn('target_donations', 'deleted_at')) {
                // Modify the existing column if needed
                $table->softDeletes()->change();
            } else {
                // Add the column if it doesn't exist
                $table->softDeletes();
            }
        });
    }
    
    public function down()
    {
        Schema::table('target_donations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
