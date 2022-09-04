<?php

use App\Models\Reservation;
use App\Models\Vacancy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_vacancy', function (Blueprint $table) {
            $table->foreignIdFor(Reservation::class)->constrained();
            $table->foreignIdFor(Vacancy::class)->constrained();

            $table->unique(["reservation_id", "vacancy_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_vacancy');
    }
};
