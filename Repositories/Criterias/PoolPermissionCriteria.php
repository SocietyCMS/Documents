<?php

namespace Modules\Documents\Repositories\Criterias;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

/**
 * Class PoolCriteria
 * @package Modules\Documents\Repositories\Criterias
 */
class PoolPermissionCriteria implements CriteriaInterface
{

    /**
     * @var
     */
    private $user;

    /**
     * PoolCriteria constructor.
     * @param $pool
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }


    /**
     * @param                     $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $repository->skipCriteria(true);

        $uidList = $repository->getUidList()->filter(function ($item) {
            return $this->user->can(["documents::pool-{$item}-read", "documents::pool-{$item}-write"]);
        });

        $repository->skipCriteria(false);

        $model = $model->whereIn('uid', $uidList);
        return $model;
    }
}