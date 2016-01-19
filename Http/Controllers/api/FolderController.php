<?php

namespace Modules\Documents\Http\Controllers\api;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\ApiBaseController;
use Modules\Documents\Repositories\Criterias\ParentCriteria;
use Modules\Documents\Repositories\Criterias\PoolCriteria;
use Modules\Documents\Repositories\Criterias\withTrashCriteria;
use Modules\Documents\Repositories\ObjectRepository;
use Modules\Documents\Repositories\Validators\FolderValidator;
use Modules\Documents\Transformers\ObjectTransformer;
use Prettus\Validator\Contracts\ValidatorInterface;


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
     * @var ObjectRepository
     */
    private $validator;

    /**
     * FileController constructor.
     * @param ObjectRepository $repository
     */
    public function __construct(ObjectRepository $repository, Request $request)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->validator = $this->repository->makeValidator(FolderValidator::class);
        $this->repository->pushCriteria(new PoolCriteria($request->pool));

        $this->middleware("permission:documents::pool-{$request->pool}-read", ['only' => ['list_folder']]);
        $this->middleware("permission:documents::pool-{$request->pool}-write", ['only' => ['create_folder']]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function list_folder(Request $request)
    {
        $parent = $this->getParent($request);

        $this->repository->pushCriteria(new ParentCriteria($request->input('parent_uid', null)));
        $this->repository->pushCriteria(new withTrashCriteria($request->input('with_trash', false)));

        $objects = $this->repository->paginate(15);

        $meta = [
            'parent_uid' => $request->input('parent_uid', null),
            'with_trash' => (bool)$request->input('with_trash', false),
            'objects'   => [
                'total' => $objects->count(),
                'files' => $objects->where('tag', 'file')->count(),
                'folders' => $objects->where('tag', 'folder')->count(),
            ],

            'containing_fq_path' => $parent?$parent->getFQPath():'',
            'containing_fq_uid' => $parent?$parent->getFQUid():'',
            'containing_ns_path' => $parent?$parent->getNSPath():'',
        ];

        return $this->response->paginator($objects, new ObjectTransformer())->setMeta($meta);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create_folder(Request $request)
    {
        if ($this->validator->with($request->input())->fails(ValidatorInterface::RULE_CREATE)) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new folder.', $this->validator->errors());
        }

        $folder = $this->repository->create([
            'title'       => $request->title,
            'description' => $request->description,
            'parent_uid'  => $this->getNullFromEmpty($request->parent_uid),
            'shared'      => $request->shared,
            'user_id'     => $this->user()->id,
            'pool_uid'    => $request->pool,
        ]);

        $folder = $this->updateComputedProperties($request, $folder);

        return $this->response->item($folder, new ObjectTransformer());
    }


    /**
     * @param Request $request
     * @param         $file
     * @return mixed
     */
    private function updateComputedProperties(Request $request, $file)
    {
        $file->tag = 'folder';
        $file->mimeType = 'application/x-directory';

        $file->save();

        return $file;
    }

    /**
     * @param Request $request
     * @return null
     */
    private function getParent(Request $request)
    {
        $parent_uid = $this->getNullFromEmpty($request->parent_uid);
        if (!is_null($parent_uid)) {
            return $this->repository->findByUid($parent_uid);

        }
        return null;
    }

    /**
     * @param $value
     * @return null
     */
    private function getNullFromEmpty($value)
    {
        return empty($value) ? null : $value;
    }
}
