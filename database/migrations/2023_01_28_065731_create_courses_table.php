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
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->unsignedBigInteger('certificate_layout_id');
            $table->foreign('certificate_layout_id')->references('id')->on('certificate_layouts')->onDelete('cascade');
            $table->string('code',50)->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('date_from', $precision = 0)->useCurrent();
            $table->dateTime('date_untill', $precision = 0)->useCurrent();
            $table->json('info')->nullable();
            $table->timestamps();
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
