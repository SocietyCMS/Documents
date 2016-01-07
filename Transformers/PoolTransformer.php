<?php

namespace Modules\Documents\Transformers;

use League\Fractal;

class PoolTransformer extends Fractal\TransformerAbstract
{
    public function transform($pool)
    {
        return [
            'uid' => $pool->uid,
            'title' => $pool->title,
            'description' => $pool->description,

            'quota' => $pool->quota,

            'files' => [
                'count' => $pool->files->count(),
            ],

            'deleted' => $pool->trashed(),

            'created_at' => $pool->created_at->toRfc3339String(),
            'updated_at' => $pool->updated_at->toRfc3339String(),
            'deleted_at' => isset($pool->deleted_at)?$pool->deleted_at->toRfc3339String():null,
        ];
    }
}
