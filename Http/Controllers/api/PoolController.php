<?php

namespace Modules\Documents\Http\Controllers\api;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\ApiBaseController;
use Modules\Documents\Repositories\PoolRepository;
use Modules\Documents\Transformers\PoolTransformer;


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
     * FileController constructor.
     * @param FileRepository $repository
     */
    public function __construct(PoolRepository $repository)
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
/*
        $this->repository->create([
            'title' => 'SocietyCMS',
            'description' => 'Bloop',
            'quota' => 1000000,
        ]);
*/
        $pools = $this->repository->paginate(15);

        return $this->response->paginator($pools, new PoolTransformer());
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $pool = null;

        return $this->response->item($pool, new PoolTransformer());
    }

    /**
     * @param Request $request
     * @param         $pool
     * @return mixed
     */
    public function get(Request $request, $pool)
    {
        $pool = $this->repository->find($pool);
        return $this->response->item($pool, new PoolTransformer());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request, $pool)
    {
        $pool = null;

        return $this->response->item($pool, new PoolTransformer());
    }

    /**
     * @param Request $request
     * @param         $pool
     * @return mixed
     */
    public function destroy(Request $request, $pool)
    {
        $pool = $this->repository->find($pool);

        $pool->delete();

        return $this->response->successDeleted();
    }
}
