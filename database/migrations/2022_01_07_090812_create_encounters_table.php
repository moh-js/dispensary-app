<?php

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEncountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->comment('Doctor ID')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'cheif')->comment('doctor ID')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(Service::class, 'purpose')->comment('service ID')->nullable()->constrained('services')->cascadeOnDelete();
            $table->text('chief_complains')->nullable();
            $table->text('amplification')->nullable();
            $table->text('review_of_systems')->nullable();
            $table->text('past_medical')->nullable();
            $table->text('social_family_history')->nullable();
            $table->text('physical_examination')->nullable();
            $table->text('systemic_examination')->nullable();
            $table->text('provisional_diagnosis')->nullable();
            $table->text('investigation')->nullable();
            $table->text('treatment')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('encounters');
    }
}
