<?php

namespace Modules\Documents\Repositories;

use Modules\Core\Repositories\Eloquent\EloquentHashidsRepository;
use Prettus\Validator\Exceptions\ValidatorException;

class ObjectRepository extends EloquentHashidsRepository
{

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return 'Modules\\Documents\\Entities\\Object';
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {
        return "Modules\\Documents\\Repositories\\Validators\\ObjectValidator";
    }
}
