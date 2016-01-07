<?php

namespace Modules\Documents\Repositories;

use Modules\Core\Repositories\Eloquent\EloquentHashidsRepository;

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
}
