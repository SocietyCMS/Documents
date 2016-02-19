<?php

namespace Modules\Documents\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Http\Controllers\ApiBaseController;
use Modules\Documents\Exceptions\QuotaExceededException;
use Modules\Documents\Repositories\Criterias\PoolCriteria;
use Modules\Documents\Repositories\Criterias\withTrashCriteria;
use Modules\Documents\Repositories\ObjectRepository;
use Modules\Documents\Repositories\PoolRepository;
use Modules\Documents\Repositories\Validators\FileValidator;
use Modules\Documents\Transformers\ObjectTransformer;
use Prettus\Validator\Contracts\ValidatorInterface;


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
     * @var ObjectRepository
     */
    private $poolRepository;

    /**
     * @var null|\Prettus\Validator\Contracts\ValidatorInterface
     */
    private $validator;


    /**
     * FileController constructor.
     * @param ObjectRepository $repository
     * @param PoolRepository   $poolRepository
     * @param Request          $request
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function __construct(ObjectRepository $repository, PoolRepository $poolRepository, Request $request)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->poolRepository = $poolRepository;
        $this->validator = $this->repository->makeValidator(FileValidator::class);
        $this->repository->pushCriteria(new PoolCriteria($request->pool));

        $this->middleware("permission:documents:unmanaged::pool-{$request->pool}-read", ['only' => ['index', 'get']]);
        $this->middleware("permission:documents:unmanaged::pool-{$request->pool}-write", ['only' => ['store', 'update', 'destroy', 'forceDestroy', 'restore']]);

    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $files = $this->repository->paginate(100);
        $meta = [
            'directory' => '/',
        ];

        return $this->response->paginator($files, new ObjectTransformer())->setMeta($meta);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if ($this->validator->with($request->input())->fails(ValidatorInterface::RULE_CREATE)) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new file.', $this->validator->errors());
        }
        if (is_null($request->file('data-binary'))) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new file.', ['data-binary is not a file']);
        }

        $this->validatePoolQuota($request);

        $file = $this->repository->create([
            'description' => $request->description,
            'parent_uid'  => $this->sanitizeUid($request->parent_uid),
            'shared'      => $request->shared,
            'user_id'     => $this->user()->id,
            'pool_uid'    => $request->pool,
        ]);

        $file = $this->updateComputedProperties($request, $file);

        Storage::disk('storage')->put(
            'documents/'.$file->uid,
            file_get_contents($request->file('data-binary')->getRealPath())
        );

        return $this->response->item($file, new ObjectTransformer());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request)
    {
        $this->repository->pushCriteria(new withTrashCriteria($request->input('with_trash', false)));
        $file = $this->repository->findByUid($request->uid);

        return $this->response->item($file, new ObjectTransformer());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        if ($this->validator->with(array_merge($request->input(), ['uid' => $request->uid]))->fails(ValidatorInterface::RULE_UPDATE)) {
            throw new \Dingo\Api\Exception\UpdateResourceFailedException('Could not update file.', $this->validator->errors());
        }

        $file_id = $this->repository->findByUid($request->uid)->id;
        $file = $this->repository->update([
            'title'       => $request->title,
            'description' => $request->description,
            'parent_uid'  => $this->sanitizeUid($request->parent_uid),
            'shared'      => $request->shared,
            'user_id'     => $this->user()->id,
            'pool_uid'    => $request->pool,
        ], $file_id);

        $file = $this->updateComputedProperties($request, $file);

        return $this->response->item($file, new ObjectTransformer());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        if ($this->validator->with(array_merge($request->input(), ['uid' => $request->uid]))->fails(ValidatorInterface::RULE_UPDATE)) {
            throw new \Dingo\Api\Exception\DeleteResourceFailedException ('Could not delete file.', $this->validator->errors());
        }

        $this->repository->delete($request->uid);

        return $this->successDeleted();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function forceDestroy(Request $request)
    {
        if ($this->validator->with(array_merge($request->input(), ['uid' => $request->uid]))->fails(ValidatorInterface::RULE_UPDATE)) {
            throw new \Dingo\Api\Exception\DeleteResourceFailedException('Could not delete file.', $this->validator->errors());
        }

        Storage::disk('storage')->delete('documents/'.$request->uid);

        $this->repository->forceDelete($request->uid);

        return $this->successDeleted();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function restore(Request $request)
    {
        if ($this->validator->with(array_merge($request->input(), ['uid' => $request->uid]))->fails(ValidatorInterface::RULE_UPDATE)) {
            throw new \Dingo\Api\Exception\UpdateResourceFailedException ('Could not restore file.', $this->validator->errors());
        }

        $this->repository->restore($request->uid);

        return $this->successRestored();
    }

    /**
     * @param Request $request
     * @param         $file
     * @return mixed
     */
    private function updateComputedProperties(Request $request, $file)
    {
        if ($request->file('data-binary')) {

            $file->title = $request->title ?: pathinfo($request->file('data-binary')->getClientOriginalName(), PATHINFO_FILENAME);

            $file->tag = 'file';

            $file->mimeType = $request->file('data-binary')->getMimeType();

            $file->originalFilename = $request->file('data-binary')->getClientOriginalName();
            $file->fileSize = $request->file('data-binary')->getSize();
            $file->fileExtension = $request->file('data-binary')->getClientOriginalExtension();

            $file->md5Checksum = md5_file($request->file('data-binary')->getPathname());

            $file->save();
        }

        return $file;
    }

    /**
     * @param $uid
     * @return null
     */
    private function sanitizeUid($uid)
    {
        if(empty($uid) || strtolower($uid) == 'null'){
            return null;
        }

        return$uid;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function validatePoolQuota(Request $request)
    {
        $pool = $this->poolRepository->findByUid($request->pool);

        if (($pool->getQuotaUsed() + $request->file('data-binary')->getSize()) >= $pool->quota){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new file.', ['The Pools Quota is exceeded']);
        }

        return true;

    }
}
