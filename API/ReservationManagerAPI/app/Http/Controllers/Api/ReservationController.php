<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Interfaces\IReservationService;

use App\Exceptions\BusinessException;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(IReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function create(Request $request){

        $validated = Validator::make($request->all(), [
            'resourceId' => 'required|integer',
            'reservedAt' => 'required|date',
            'duration' => 'required|integer|min:1',
        ]);
        
        if ($validated->fails()) {
            return response()->json($validated->errors(),400);
        }

        $resource = $this->getReserveToCreate($request);
        $response = $this->reservationService->create($resource);
        try {
            

            return response()->json($response,200);
        } 
        catch (BusinessException $e) {
            $error = ['message' => $e->getMessage() ];
            return response()->json($error , 400);
        }
        catch (\Exception $e){
            $error = ['message' => 'Error interno, intente mas tarde' ];
            return response()->json($error , 400);
        }

        
    }

    /** privados */
    private function getReserveToCreate(Request $request){
        return [
            'resource_id' => $request->resourceId,
            'reserved_at' => $request->reservedAt,
            'duration' => $request->duration,
        ];
    }
    
}
