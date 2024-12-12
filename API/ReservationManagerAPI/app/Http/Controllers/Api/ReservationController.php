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

    //Endpoint para crear una reserva
    public function create(Request $request){

        //Validación de campos requeridos
        $validated = Validator::make($request->all(), [
            'resourceId' => 'required|integer',
            'reservedAt' => 'required|date',
            'duration' => 'required|integer|min:1',
        ]);
        
        //Si los datos no son validos
        if ($validated->fails()) {
            //Respuesta con los errores en la validación
            return response()->json($validated->errors(),400);
        }

        //Obteniendo los datos en el request a un objeto DTO
        $resource = $this->getReserveToCreate($request);
        
        try {
            //Se envia datos al servicio para la creación
            $response = $this->reservationService->create($resource);

            //Respuesta de la petición
            return response()->json($response,200);
        } 
        //Errores controlados en el servidor
        catch (BusinessException $e) {
            $error = ['message' => $e->getMessage() ];
            return response()->json($error , 400);
        }
        //Errores no controlados
        catch (\Exception $e){
            $error = ['message' => 'Error interno, intente mas tarde' ];
            return response()->json($error , 400);
        }        
    }

    //Endpoint para cancelar una reserva
    public function cancel($id){
        
        try {

            //Validando el ID
            if (ctype_digit($id)) {
                //Se envia datos al servicio para la cancelación
                $response = $this->reservationService->cancel((int)$id);

                $data = [
                    'message' => 'cancelación exitosa!',
                    'data' => $response
                ];

                //Respuesta de la petición
                return response()->json($data,200);
            } 
            //Si no es valido
            else {
                throw new BusinessException('Reserva invalida');
            }
        } 
        //Errores controlados en el servidor
        catch (BusinessException $e) {
            $error = ['message' => $e->getMessage() ];
            return response()->json($error , 400);
        }
        //Errores no controlados
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
