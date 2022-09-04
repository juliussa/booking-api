<?php

namespace App\Http\Controllers;

use App\Constants\ResponseStatusConstants;
use App\Http\Requests\ReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Property;
use App\Models\Reservation;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;

class ReservationsController extends Controller
{
    public function __construct(public readonly ReservationService $reservationService)
    {
    }

    public function index()
    {
        return ReservationResource::collection(Reservation::all());
    }

    public function store(ReservationRequest $request)
    {
        $checkInAt = Carbon::parse($request->check_in_at);
        $checkOutAt = Carbon::parse($request->check_out_at);
        $requestVacancies = (int) $request->request_vacancies;
        $property = Property::find($request->property_id);

        if (!$property) {
            return response()->json([
                'status' => ResponseStatusConstants::ERROR,
                "message" => "Property does not exist"
            ], 404);
        }


        $isAvailable = $this->reservationService->checkAvailability($checkInAt, $checkOutAt, $requestVacancies, $property);

        if (!$isAvailable) {
            return response()->json([
                'status' => ResponseStatusConstants::ERROR,
                "message" => "Unavailable vacancies"
            ], 400);
        }

        try {
            $reservation =  $this->reservationService->create($checkInAt, $checkOutAt, $requestVacancies, $property);
            return new ReservationResource($reservation);
        } catch(Throwable $e) {
            return response()->json([
                'status' => ResponseStatusConstants::ERROR,
                "message" => $e->getMessage()
            ], 404);
        }
    }
}
