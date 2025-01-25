<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description');
            $table->string('company', 200)->nullable();
            $table->string('location', 200)->nullable();
            $table->decimal('salary_from', 10, 2)->nullable();
            $table->decimal('salary_to', 10, 2)->nullable();
            $table->text('tags')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('posted_by_user_id');

            $table->foreign('posted_by_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
