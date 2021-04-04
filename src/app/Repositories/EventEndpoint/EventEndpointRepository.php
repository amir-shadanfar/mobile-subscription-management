<?php

namespace App\Repositories\EventEndpoint;

use App\EventEndpoint;
use App\Repositories\Repository;

class EventEndpointRepository extends Repository
{

    /**
     * EventEndpointRepository constructor.
     * @param EventEndpoint $model
     */
    public function __construct(EventEndpoint $model)
    {
        parent::__construct($model);
    }

}
