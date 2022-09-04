<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Property::factory()
            ->count(5)
            ->create()
            ->each(function ($property) {
                Vacancy::factory()
                            ->count(10)
                            ->sequence(fn ($sequence) => [
                                'property_id' => $property->id,
                                'date' => Carbon::now()->addDays($sequence->index), 
                                'total_count' => 4
                            ])
                            ->create();
    });
    }
}
