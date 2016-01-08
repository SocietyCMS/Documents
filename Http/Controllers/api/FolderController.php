<?php

namespace Modules\Documents\Http\Controllers\api;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\ApiBaseController;
use Modules\Documents\Repositories\Criterias\ParentCriteria;
use Modules\Documents\Repositories\Criterias\withTrashCriteria;
use Modules\Documents\Repositories\ObjectRepository;
use Modules\Documents\Transformers\ObjectTransformer;


/**
 * Class FileController
 * @package Modules\Documents\Http\Controllers\api
 */
class FolderController extends ApiBaseController
{

    /**
     * @var ObjectRepository
     */
    private $repository;

    /**
     * FileController constructor.
     * @param ObjectRepository $repository
     */
    public function __construct(ObjectRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function list_folder(Request $request)
    {
        $meta = [
            'parent_uid' => $request->input('parent_uid', null),
            'with_trash' => (bool)$request->input('with_trash', false)
        ];

        $this->repository->pushCriteria(new ParentCriteria($request->input('parent_uid', null)));
        $this->repository->pushCriteria(new withTrashCriteria($request->input('with_trash', false)));

        $files = $this->repository->paginate(15);

        return $this->response->paginator($files, new ObjectTransformer())->setMeta($meta);
    }
}
