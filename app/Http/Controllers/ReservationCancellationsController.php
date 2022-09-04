<?php

namespace App\Http\Controllers;

use App\Constants\ResponseStatusConstants;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Throwable;

class ReservationCancellationsController extends Controller
{
    public function __construct(public readonly ReservationService $reservationService)
    {
    }

    public function store(int $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'status' => ResponseStatusConstants::ERROR,
                'message' => "Reservation not found"
            ], 404);
        }

        try {
            $this->reservationService->cancel($reservation);

            return response()->json([
                'status' => ResponseStatusConstants::SUCCESS,
                'message' => "Reservation cancelled",
            ], 200);
        } catch(Throwable $e) {
            return response()->json([
                'status' => ResponseStatusConstants::ERROR,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
