<?php

use App\Models\OrderService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderServiceIdFieldInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigations', function (Blueprint $table) {
            $table->foreignIdFor(OrderService::class, 'order_service_id')->after('service_id')->nullable()->constrained('order_service')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investigations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_service_id');
        });
    }
}
