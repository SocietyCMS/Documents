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
class DownloadController extends ApiBaseController
{

    /**
     * @var FileRepository
     */
    private $repository;


    /**
     * FileController constructor.
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @param         $file
     * @return mixed
     */
    public function download(Request $request, $file)
    {
        $file = $this->repository->find($file);
        return $this->response->item($file, new FileTransformer());
    }
}
