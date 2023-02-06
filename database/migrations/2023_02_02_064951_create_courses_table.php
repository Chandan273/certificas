<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unsignedBigInteger('certificate_layout_id');
            $table->foreign('certificate_layout_id')->references('id')->on('certificate_layouts')->onDelete('cascade');
            $table->string('code',50)->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->date('date_from', $precision = 0)->nullable();
            $table->date('date_untill', $precision = 0)->nullable();
            $table->json('info')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
