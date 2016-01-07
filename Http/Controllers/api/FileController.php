<?php

namespace Modules\Documents\Http\Controllers\api;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\ApiBaseController;
use Modules\Documents\Repositories\Criterias\PoolCriteria;
use Modules\Documents\Repositories\ObjectRepository;
use Modules\Documents\Transformers\FileTransformer;


/**
 * Class FileController
 * @package Modules\Documents\Http\Controllers\api
 */
class FileController extends ApiBaseController
{

    /**
     * @var ObjectRepository
     */
    private $repository;


    /**
     * FileController constructor.
     * @param ObjectRepository $repository
     */
    public function __construct(ObjectRepository $repository, Request $request)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->repository->pushCriteria(new PoolCriteria($request->pool));
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $files = $this->repository->paginate(15);
        $meta = [
            'directory' => '/'
        ];

        return $this->response->paginator($files, new FileTransformer())->setMeta($meta);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $file = null;

        return $this->response->item($file, new FileTransformer());
    }

    /**
     * @param Request $request
     * @param         $file
     * @return mixed
     */
    public function get(Request $request, $file)
    {
        $file = $this->repository->findByUid($file);
        return $this->response->item($file, new FileTransformer());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request, $file)
    {
        $file = null;

        return $this->response->item($file, new FileTransformer());
    }

    /**
     * @param Request $request
     * @param         $file
     * @return mixed
     */
    public function destroy(Request $request, $file)
    {
        $file = $this->repository->find($file);

        $file->delete();

        return $this->response->successDeleted();
    }
}
