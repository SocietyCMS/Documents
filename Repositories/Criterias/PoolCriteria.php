<?php

namespace Modules\Documents\Repositories\Criterias;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

/**
 * Class PoolCriteria
 * @package Modules\Documents\Repositories\Criterias
 */
class PoolCriteria implements CriteriaInterface
{
    /**
     * @var
     */
    private $pool;

    /**
     * PoolCriteria constructor.
     */
    public function __construct($pool)
    {
        $this->pool = $pool;
    }


    /**
     * @param                     $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where('pool_uid', '=', $this->pool);
        return $model;
    }
}