<?php

namespace App\Repositories\Application;

use App\Application;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;

class ApplicationRepository extends Repository
{
    /**
     * ApplicationRepository constructor.
     * @param Application $model
     */
    public function __construct(Application $model)
    {
        parent::__construct($model);
    }

    public function find($id): ?Model
    {
        if (is_int($id))
            return parent::find($id);
        else
            return $this->model->where('code', $id)->first();
    }

}
