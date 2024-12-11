<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Interfaces\IResourceService;

use App\Exceptions\BusinessException;

//DTOs
use App\DTOs\ResourceDTO;

class ResourceController extends Controller
{
    //
    protected $resourceService;

    public function __construct(IResourceService $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    public function getAll(){
        $response = $this->resourceService->getAll();
        
        return response()->json($response,200);
    }

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
