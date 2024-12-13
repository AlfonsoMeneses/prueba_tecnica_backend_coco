<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationEndpointsTest extends TestCase
{
    protected $resourceId = 1;
    protected $reservedAt = '2024-12-16 16:30';
    protected $duration = 2;
    protected $reservationId = 0;

    //Test para la creación de una reservación
    public function test_create_a_reservation_when_resource_is_available(): void
    {
       
        $data = [
            'resourceId' => $this->resourceId,
            'reservedAt' => $this->reservedAt,
            'duration' => $this->duration,
        ];

        //Enviando 
        $response = $this->post('/api/reservations', $data);

        // Verifica el registro en la base de datos manualmente
        $reservation = \DB::table('reservations')
                        ->where('resource_id', $this->resourceId)
                        ->where('reserved_at', $this->reservedAt)
                        ->where('duration', $this->duration)
                        ->first();

        //Validando si se creo la reservación
        $this->assertNotNull($reservation);
        
        //Validando la respuesta
        $response->assertStatus(200);
    }

    //Test para validar que no se puede crear una reservación con un recurso no disponible en la fecha seleccionada
    public function test_cannot_create_reservation_when_resource_is_unavailable(): void
    {
        $data = [
            'resourceId' => $this->resourceId,
            'reservedAt' => $this->reservedAt,
            'duration' => $this->duration,
        ];

        $response = $this->post('/api/reservations', $data);

        $response->assertStatus(400);
    }

}
