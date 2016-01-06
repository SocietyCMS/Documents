<?php

namespace Modules\Documents\Transformers;

use League\Fractal;

class FileTransformer extends Fractal\TransformerAbstract
{
    public function transform($file)
    {
        return [
            'id' => $file->id,
            'title' => $file->title,
            'description' => $file->description,

            'mimeType' => $file->mimeType,
            'originalFilename' => $file->originalFilename,
            'md5Checksum' => $file->md5Checksum,
            'fileSize' => $file->fileSize,

            'downloadUrl' => apiRoute('v1', 'api.documents.download.show', $file->id),

            'shared' => (bool)$file->shared,

            'owner' => $file->user_id,

            'deleted' => $file->trashed(),

            'created_at' => $file->created_at->toRfc3339String(),
            'updated_at' => $file->updated_at->toRfc3339String(),
            'deleted_at' => isset($file->deleted_at)?$file->deleted_at->toRfc3339String():null,
        ];
    }
}
