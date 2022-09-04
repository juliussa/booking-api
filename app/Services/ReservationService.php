<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Reservation;
use App\Models\Vacancy;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class ReservationService
{
    public function create(Carbon $checkInAt, Carbon $checkOutAt, int $requestVacancies, Property $property): Reservation
    {
        try {
            DB::beginTransaction();

            $vacancies = $this->vacanciesQuery($checkInAt, $checkOutAt, $property)->get();

            $totalPrice = $vacancies
                ->reduce(function ($carry, $item) use ($requestVacancies) {
                    return $carry + ($item->price * $requestVacancies);
                }, 0);

            $reservation = Reservation::create([
                'check_in_at' => $checkInAt,
                'check_out_at' => $checkOutAt,
                'request_vacancies' => $requestVacancies,
                'total_price' => $totalPrice
            ]);

            $ids = $vacancies->pluck('id')->toArray();

            Vacancy::whereIn("id", $ids)->increment('reserved_count', $requestVacancies);

            $reservation->vacancies()->attach($ids);

            DB::commit();

            return $reservation;
        } catch(Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function checkAvailability(Carbon $checkInAt, Carbon $checkOutAt, int $requestVacancies, Property $property): bool
    {
        $period = CarbonPeriod::create($checkInAt, $checkOutAt);
        $dates = array_map(fn ($date) => $date->format("Y-m-d"), $period->toArray());

        $vacancies = $this->vacanciesQuery($checkInAt, $checkOutAt, $property)
            ->whereRaw('total_count - reserved_count >= ?', [$requestVacancies])
            ->pluck("date")
            ->toArray();
        $missingDates = array_diff($dates, $vacancies);

        return count($missingDates) > 0 ? false : true;
    }


    public function cancel(Reservation $reservation): void
    {
        throw_if($reservation->canceled_at !== null, "Reservation is already canceled");

        $reservation->update(['canceled_at' => now()]);

        $reservation->vacancies()->decrement('reserved_count', $reservation->request_vacancies);
    }

    private function vacanciesQuery(Carbon $checkInAt, Carbon $checkOutAt, Property $property): Builder
    {
        return Vacancy::whereBetween("date", [$checkInAt, $checkOutAt])
            ->where('property_id', $property->id);
    }
}
