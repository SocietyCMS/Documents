<?php

namespace Modules\Documents\Repositories;

use Modules\Core\Repositories\Eloquent\EloquentHashidsRepository;

class FileRepository extends EloquentHashidsRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return 'Modules\\Documents\\Entities\\File';
    }
}
