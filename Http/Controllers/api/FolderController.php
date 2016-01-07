<?php

namespace Modules\Documents\Http\Controllers\api;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\ApiBaseController;
use Modules\Documents\Repositories\ObjectRepository;
use Modules\Documents\Transformers\FileTransformer;


/**
 * Class FileController
 * @package Modules\Documents\Http\Controllers\api
 */
class FolderController extends ApiBaseController
{

    /**
     * @var FileRepository
     */
    private $repository;


    /**
     * FileController constructor.
     * @param FileRepository $repository
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
            'directory' => $request->input('path', '/'),
            'recursive' => $request->input('recursive', false),
            'include_deleted' => $request->input('include_deleted', false)
        ];

        $files = $this->repository->paginate(15);

        return $this->response->paginator($files, new FileTransformer())->setMeta($meta);
    }
}
