<?php

namespace Modules\Documents\Http\Controllers\api;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\ApiBaseController;
use Modules\Documents\Repositories\PoolRepository;
use Modules\Documents\Transformers\PoolTransformer;
use Prettus\Validator\Contracts\ValidatorInterface;


/**
 * Class FileController
 * @package Modules\Documents\Http\Controllers\api
 */
class PoolController extends ApiBaseController
{

    /**
     * @var PoolRepository
     */
    private $repository;

    /**
     * @var null|\Prettus\Validator\Contracts\ValidatorInterface
     */
    private $validator;


    /**
     * FileController constructor.
     * @param PoolRepository $repository
     */
    public function __construct(PoolRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->validator = $this->repository->makeValidator();
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        /*
        foreach ($this->repository->all() as $item) {
            $permissionManager = new \Modules\Core\Permissions\PermissionManager();
            $permissionManager->registerPermission(
                "documents::pool-{$item->uid}",
                $item->title,
                $item->description,
                "documents"
            );
        }
        */

        $pools = collect();
        foreach ($this->repository->all() as $item) {
            if($this->auth->user()->can("documents::pool-{$item->uid}"))
            {
                $pools->push($item);
            }
        }

        return $this->response->collection($pools, new PoolTransformer());
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if ($this->validator->with($request->input())->fails(ValidatorInterface::RULE_CREATE)) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new pool.', $this->validator->errors());
        }

        $pool = $this->repository->create([
            'title'       => $request->title,
            'description' => $request->description,
            'quota'       => $request->quota,
        ]);

        return $this->response->item($pool, new PoolTransformer());
    }

    /**
     * @param Request $request
     * @param         $pool
     * @return mixed
     */
    public function get(Request $request)
    {
        $pool = $this->repository->findByUid($request->pool);

        return $this->response->item($pool, new PoolTransformer());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        if ($this->validator->with(array_merge($request->input(), ['uid' => $request->pool]))->fails(ValidatorInterface::RULE_UPDATE)) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new pool.', $this->validator->errors());
        }

        $pool_id = $this->repository->findByUid($request->pool)->id;
        $item = $this->repository->update([
            'title'       => $request->title,
            'description' => $request->description,
            'quota'       => $request->quota,
        ], $pool_id);

        return $this->response->item($item, new PoolTransformer());
    }

    /**
     * @param Request $request
     * @param         $pool
     * @return mixed
     */
    public function destroy(Request $request)
    {
        $pool = $this->repository->findByUid($request->pool);

        $pool->delete();

        return $this->successDeleted();
    }
}
