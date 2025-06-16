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
        Schema::create('ga4_qsha_oh', function (Blueprint $table) {
            $table->id();
            $table->string('landing_url');
            $table->string('session_medium');
            $table->integer('total_sessions')->default(0);
            $table->integer('cv_count')->default(0);
            $table->float('cvr')->default(0); // CVRはパーセンテージとして保存（例：23.45）
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps(); // created_at, updated_at
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
