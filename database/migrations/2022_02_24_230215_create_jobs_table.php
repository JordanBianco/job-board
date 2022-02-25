<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company');
            $table->string('title')->unique();
            $table->string('slug');
            $table->text('position');
            $table->text('description');
            $table->string('logo')->nullable();
            $table->string('location');
            $table->integer('min_salary');
            $table->integer('max_salary');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();


            // $table->integer('min_years_experience');
            // $table->integer('min_study');
            // $table->integer('contratto');
            // $table->integer('giornata lavorativa'); / full - part time
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
