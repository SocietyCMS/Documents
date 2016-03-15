<?php

namespace Modules\Documents\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Http\Controllers\ApiBaseController;
use Modules\Documents\Http\Requests\ApiRequest;
use Modules\Documents\Repositories\Criterias\PoolCriteria;
use Modules\Documents\Repositories\ObjectRepository;


/**
 * Class FileController
 * @package Modules\Documents\Http\Controllers\api
 */
class DownloadController extends ApiBaseController
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

        $this->middleware("permission:documents::pool-{$request->pool}-read", ['only' => ['index', 'get']]);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function download(ApiRequest $request)
    {
        $file = $this->repository->findByUid($request->file);
        $contents = Storage::disk('storage')->get( 'documents/'.$file->uid);

        return (new Response($contents, 200))
            ->header('Content-Type', $file->mimeType)
            ->header('Content-Length', $file->fileSize)
            ->header('Content-Disposition',"attachment; filename='{$file->title}.{$file->fileExtension}'");
    }
}
