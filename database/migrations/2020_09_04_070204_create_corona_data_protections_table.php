<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoronaDataProtectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corona_data_protections', function (Blueprint $table) {
            $table->id();
            $table->string('privacy_name');
            $table->string('facility_name');
            $table->string('facility_address');
            $table->string('facility_location');
            $table->string('owner_name');
            $table->string('telephone_number');
            $table->timestamps();

            $table->foreignId('facility_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('corona_data_protections');
    }
}
