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
     * @var FileRepository
     */
    private $repository;


    /**
     * FileController constructor.
     * @param FileRepository $repository
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
        /*return $this->repository->create([
            'title' => 'File1',
            'mimeType' => 'application/pdf',
            'tag' => 'file',
            'description' => 'A typical PDF',
            'originalFilename' => 'non_special_pdf_v1.pdf',
            'fileExtension' => 'pdf',
            'md5Checksum' => md5('non_special_pdf_v1.pdf'),
            'fileSize' => 1789415,
            'shared' => false,
            'user_id' => 1,
        ]);
*/
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
