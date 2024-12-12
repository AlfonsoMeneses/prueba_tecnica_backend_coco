<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//Interfaces
use App\Interfaces\IResourceService;

//Exceptiones
use App\Exceptions\BusinessException;

//DTOs
use App\DTOs\ResourceDTO;
use App\DTOs\QueryAvailabilityResourceDTO;

class ResourceController extends Controller
{
    //
    protected $resourceService;

    public function __construct(IResourceService $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    //Endpoint para obtener todos los recursos activos
    public function getAll(){
        try{
            $response = $this->resourceService->getAll();
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

    //Endpoint para la consulta de la disponibilidad de un recurso
    public function getResourceAvailability(Request $request, $id){
        try {

             //Validando el ID
             if (!ctype_digit($id)) {
                throw new BusinessException('El recurso seleccionado no existe.');
             }

            //Validando rango de fechas
            $validator = Validator::make($request->all(), [
                'beginDate' => 'required|date', 
                'endDate' => 'required|date',   
            ]);

             // Si la validación falla, lanzamos la excepción manualmente
            if ($validator->fails()) {
                throw new BusinessException('Falta el rango de fechas para la consulta, o los parámetros son inválidos.');
            }

            $query = new QueryAvailabilityResourceDTO([
                'resourceId' => $id,
                'beginDate' => $request->beginDate,
                'endDate' => $request->endDate
            ]);
            
            $response = $this->resourceService->getResourceAvailability($query);

            $data = [
                "availability" =>$response,
            ];

            return response()->json($data,200);
        }
        catch (BusinessException $e) {
            $error = ['message' => $e->getMessage() ];
            return response()->json($error , 400);
        }
        catch (\Exception $e){
            //echo $e;
            $error = ['message' =>'Error interno, intente mas tarde' ];
            return response()->json($error , 400);
        }        
    }

    //Endpoint para la creación de un recurso
    public function create(Request $request){

        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'capacity' => 'required|integer|min:1',
            'resource_type_id' => 'required',
        ]);
        
        if ($validated->fails()) {
            return response()->json($validated->errors(),400);
        }

        $resource = $this->getResource($request);

        try {
            $response = $this->resourceService->create($resource);

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

    

    /**privadas */
    private function getResource(Request $request){
        return new ResourceDTO([
            'id' =>$request->id  != null ? $request->id : 0,
            'name' => $request->name,
            'description' => $request->description != null ? $request->description : "" ,
            'capacity' => $request->capacity,
            'resource_type_id' => $request->resource_type_id
        ]);
    }
}
