<?php

namespace Modules\Documents\Repositories\Criterias;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

/**
 * Class PoolCriteria
 * @package Modules\Documents\Repositories\Criterias
 */
class ParentCriteria implements CriteriaInterface
{
    /**
     * @var
     */
    private $parent_uid;

    /**
     * PoolCriteria constructor.
     * @param $parent_uid
     */
    public function __construct($parent_uid)
    {
        $this->parent_uid = empty($parent_uid)?null:$parent_uid;
    }


    /**
     * @param                     $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where('parent_uid', '=', $this->parent_uid);
        return $model;
    }
}
