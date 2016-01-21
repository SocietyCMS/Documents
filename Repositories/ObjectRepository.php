<?php

namespace Modules\Documents\Repositories;

use Modules\Core\Repositories\Eloquent\EloquentHashidsRepository;
use Modules\Documents\Repositories\Criterias\withTrashCriteria;
use Prettus\Repository\Events\RepositoryEntityDeleted;
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

    /**
     * Delete a entity in repository by uid
     *
     * @param $uid
     * @return int
     */
    public function delete($uid)
    {
        $this->applyScope();

        $_skipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $model = $this->findByUid($uid);
        $originalModel = clone $model;

        $this->skipPresenter($_skipPresenter);
        $this->resetModel();

        $deleted = $model->delete();

        event(new RepositoryEntityDeleted($this, $originalModel));

        return $deleted;
    }

    /**
     * Force-Delete a entity in repository by uid
     *
     * @param $uid
     * @return int
     */
    public function forceDelete($uid)
    {
        $this->pushCriteria(new withTrashCriteria(true));

        $this->applyScope();

        $_skipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $model = $this->findByUid($uid);
        $originalModel = clone $model;

        $this->skipPresenter($_skipPresenter);
        $this->resetModel();

        $deleted = $model->forceDelete();

        event(new RepositoryEntityDeleted($this, $originalModel));

        return $deleted;
    }
}
