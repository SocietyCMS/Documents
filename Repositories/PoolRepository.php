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


    /**
     * Get a list of all Uid's in the database, return cache if possible.
     * @return mixed
     */
    public function getUidList()
    {
        if ( $this->isSkippedCache() ){
            return $this->queryUidList();
        }

        $key     = $this->getCacheKey('uidList', func_get_args());
        $minutes = $this->getCacheMinutes();
        $value   = $this->getCacheRepository()->remember($key, $minutes, function() {
            return $this->queryUidList();
        });

        return $value;
    }

    /**
     * Get a list of all Uid's in the database
     * @return mixed
     */
    public function queryUidList()
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->lists('uid');
        $this->resetModel();

        return $this->parserResult($model);
    }

}
