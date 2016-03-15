<?php

namespace Modules\Documents\Transformers;

use League\Fractal;

class PoolTransformer extends Fractal\TransformerAbstract
{
    public function transform($pool)
    {
        return [
            'uid'            => $pool->uid,
            'title'          => $pool->title,
            'description'    => $pool->description,
            'quota'          => (int)$pool->quota,
            'quotaUsed'      => $pool->getQuotaUsed(),
            'objects'        => [
                'total'   => $pool->count(),
                'files'   => $pool->objects->where('tag', 'file')->count(),
                'folders' => $pool->objects->where('tag', 'folder')->count(),
            ],
            'userPermissions' => [
                'read'  => $this->user()->can("documents:unmanaged::pool-{$pool->uid}-read"),
                'write' => $this->user()->can("documents:unmanaged::pool-{$pool->uid}-write"),
            ],
            'permissions' => [
                'read' => $this->rolesByPermission("documents:unmanaged::pool-{$pool->uid}-read"),
                'write' => $this->rolesByPermission("documents:unmanaged::pool-{$pool->uid}-write"),
            ],
            'deleted'        => (bool)$pool->trashed(),
            'created_at'     => $pool->created_at->toRfc3339String(),
            'updated_at'     => $pool->updated_at->toRfc3339String(),
            'deleted_at'     => isset($pool->deleted_at) ? $pool->deleted_at->toRfc3339String() : null,
        ];
    }

    private function user()
    {
        return app('Dingo\Api\Auth\Auth')->user();
    }

    private function rolesByPermission($permission)
    {
        $permissionManager = new \Modules\Core\Permissions\PermissionManager();
        return $permissionManager->getPermission($permission)->roles()->lists('id');
    }
}
