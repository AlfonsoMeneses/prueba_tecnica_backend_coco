<?php

namespace App\DTOs;

class QueryAvailabilityResourceDTO{

    public  $resourceId;
    public  $beginDate;
    public  $endDate;

    public function __construct(array $data)
    {
        $this->resourceId = $data['resourceId'] ?? 0;
        $this->beginDate = $data['beginDate'] ?? new date();
        $this->endDate = $data['endDate'] ?? 0;
    }

}