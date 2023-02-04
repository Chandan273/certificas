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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->string('number',50)->nullable();
            $table->string('name')->nullable();
            $table->string('contact')->nullable();
            $table->string('organisation_number')->nullable();
            $table->string('email')->nullable();
            $table->string('www')->nullable();
            $table->string('phone',25)->nullable();
            $table->string('address')->nullable();
            $table->string('zip',10)->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
