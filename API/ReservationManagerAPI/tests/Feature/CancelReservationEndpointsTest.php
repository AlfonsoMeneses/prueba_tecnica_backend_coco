<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CancelReservationEndpointsTest extends TestCase
{
   //Test para la cancelación de una reservación
   public function test_cancel_reservation_ok(): void
   {
        //ID Reservación
        $reservationId = 21;
        
        //ID Estado Cancelado
        $statusId = 3;
     
        //Enviando 
        $response = $this->delete('/api/reservations/'.$reservationId);

        // Verifica el registro en la base de datos manualmente
        $reservation = \DB::table('reservations')
                        ->where('id', $reservationId)
                        ->where('status_id', $statusId)
                        ->first();

        //Validando si se creo la reservación
        $this->assertNotNull($reservation);
        
        //Validando la respuesta
        $response->assertStatus(200);
   }

}
