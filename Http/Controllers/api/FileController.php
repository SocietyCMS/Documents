<?php

namespace Modules\Documents\Http\Controllers\api;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\ApiBaseController;
use Modules\Documents\Repositories\FileRepository;
use Modules\Documents\Transformers\FileTransformer;


/**
 * Class FileController
 * @package Modules\Documents\Http\Controllers\api
 */
class FileController extends ApiBaseController
{

    /**
     * @var FileRepository
     */
    private $repository;


    /**
     * FileController constructor.
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository, Request $request)
    {
        parent::__construct();

        $this->repository = $repository;
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $files = $this->repository->paginate(15);

        return $this->response->paginator($files, new FileTransformer());
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
        $file = $this->repository->find($file);
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
