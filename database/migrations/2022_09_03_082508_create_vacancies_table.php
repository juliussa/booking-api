<?php

use App\Models\Property;
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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignIdFor(Property::class)->constrained();
            $table->integer("total_count")->unsigned();
            $table->integer("reserved_count")->unsigned();
            $table->integer("price");
            $table->timestamps();

            $table->unique(['date', 'property_id']);
        });

        DB::statement('ALTER TABLE vacancies ADD CONSTRAINT chk_vacancies_count CHECK (total_count >= reserved_count);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancies');
    }
};
