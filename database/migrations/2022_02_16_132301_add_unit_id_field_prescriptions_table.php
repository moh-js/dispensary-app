<?php

use App\Models\OrderService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitIdFieldPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->foreignId('unit_id')->after('service_id')->nullable()->constrained()->cascadeOnDelete();
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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unit_id');
            // $table->dropConstrainedForeignId('order_service_id');
        });
    }
}
