<?php

namespace Modules\Documents\Transformers;

use League\Fractal;

class ObjectTransformer extends Fractal\TransformerAbstract
{
    public function transform($file)
    {
        return $this->{$file->tag . 'Transformer'}($file);
    }

    protected function fileTransformer($file)
    {
        return [
            'uid'              => $file->uid,
            'title'            => $file->title,
            'description'      => $file->description,
            'tag'              => 'file',
            'name'             => $file->getObjectName(),
            'parent_uid'       => $file->parent_uid,
            'mimeType'         => $file->mimeType,
            'originalFilename' => $file->originalFilename,
            'md5Checksum'      => $file->md5Checksum,
            'fileSize'         => (int)$file->fileSize,
            "path_lower"       => $file->getPath(),
            'downloadUrl'      => apiRoute('v1', 'api.documents.file.download', ['pool_id' => $file->pool_uid, 'id' => $file->uid]),
            'shared'           => (bool)$file->shared,
            'owner'            => $file->user_id,
            'deleted'          => (bool)$file->trashed(),
            'created_at'       => $file->created_at->toRfc3339String(),
            'updated_at'       => $file->updated_at->toRfc3339String(),
            'deleted_at'       => isset($file->deleted_at) ? $file->deleted_at->toRfc3339String() : null,
            'pool_uid'         => $file->pool_uid,
        ];
    }

    protected function folderTransformer($file)
    {
        return [
            'uid'         => $file->uid,
            'title'       => $file->title,
            'description' => $file->description,
            'tag'         => 'folder',
            'name'        => $file->getObjectName(),
            "path_lower"  => $file->getPath(),
            'parent_uid'  => $file->parent_uid,
            'mimeType'    => $file->mimeType,
            'shared'      => (bool)$file->shared,
            'owner'       => $file->user_id,
            'deleted'     => (bool)$file->trashed(),
            'created_at'  => $file->created_at->toRfc3339String(),
            'updated_at'  => $file->updated_at->toRfc3339String(),
            'deleted_at'  => isset($file->deleted_at) ? $file->deleted_at->toRfc3339String() : null,
            'pool_uid'    => $file->pool_uid,
        ];
    }
}
