<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('patient_id')->unique();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('file_number')->nullable();
            $table->boolean('is_special_group')->default(0);
            $table->string('special_group_evidence')->nullable();
            $table->string('special_group_category')->nullable();
            $table->enum('gender', [
                'male', 'female'
            ]);
            $table->string('status')->default('active');
            $table->softDeletes();
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
        Schema::dropIfExists('patients');
    }
}
