<?php

namespace Modules\Documents\Repositories\Criterias;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

/**
 * Class PoolCriteria
 * @package Modules\Documents\Repositories\Criterias
 */
class withTrashCriteria implements CriteriaInterface
{
    /**
     * @var
     */
    private $with_trash;

    /**
     * PoolCriteria constructor.
     * @param $with_trash
     */
    public function __construct($with_trash)
    {
        $this->with_trash = (bool) $with_trash;
    }


    /**
     * @param                     $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if($this->with_trash)
        {
            $model = $model->withTrashed();
        }

        return $model;
    }
}