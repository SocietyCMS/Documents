<?php

namespace Modules\Documents\Repositories;

use Modules\Core\Repositories\Eloquent\EloquentHashidsRepository;

/**
 * Class PoolRepository
 * @package Modules\Documents\Repositories
 */
class PoolRepository extends EloquentHashidsRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return 'Modules\\Documents\\Entities\\Pool';
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {
        return "Modules\\Documents\\Repositories\\Validators\\PoolValidator";
    }

}
