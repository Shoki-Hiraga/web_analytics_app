<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gsc_qsha_oh', function (Blueprint $table) {
            $table->id();
            $table->string('page_url');
            $table->integer('total_impressions')->default(0);
            $table->integer('total_clicks')->default(0);
            $table->float('avg_ctr')->default(0); // 例: 0.1234 → 12.34%
            $table->float('avg_position')->default(0); // 例: 3.25
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gsc_qsha_oh');
    }
};
