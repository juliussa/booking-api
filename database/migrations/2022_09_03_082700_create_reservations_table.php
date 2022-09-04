<?php

use App\Models\Property;
use App\Models\Vacancy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->timestamp('check_in_at');
            $table->timestamp('check_out_at');
            $table->timestamp('canceled_at')->nullable()->default(null);
            $table->integer("request_vacancies")->unsignedSmallInteger();
            $table->integer('total_price');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE reservations ADD CONSTRAINT chk_reservations_date CHECK (check_in_at <= check_out_at);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
