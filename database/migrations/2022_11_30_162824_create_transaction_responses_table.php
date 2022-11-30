<?php

use App\Models\OrderDescription;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderDescription::class)->constrained()->cascadeOnDelete();
            $table->string('xendit_id')->unique()->index();
            $table->string('reference_id')->unique()->index();
            $table->string('status')->index();
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
        Schema::dropIfExists('transaction_responses');
    }
}
