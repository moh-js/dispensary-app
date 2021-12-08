<?php

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['receive', 'sent'])->nullable();
            $table->string('from')->nullable();
            $table->foreignIdFor(Unit::class, 'to')->nullable()->constrained('units')->cascadeOnDelete();
            $table->integer('remain_from')->nullable();
            $table->integer('remain_to')->nullable();
            $table->string('quantity')->nullable();
            $table->foreignIdFor(User::class, 'issued_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->softDeletes();
            $table->string('slug')->nullable();
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
        Schema::dropIfExists('ledgers');
    }
}
