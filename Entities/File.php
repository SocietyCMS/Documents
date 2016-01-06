<?php

namespace Modules\Documents\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Traits\Activity\RecordsActivity;
use Vinkla\Hashids\Facades\Hashids;


/**
 * Class Event
 * @package Modules\Calendar\Entities
 */
class File extends Model
{
    use RecordsActivity;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documents__file';

    /**
     * The fillable properties of the model.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'mimeType',
        'description',
        'originalFilename',
        'fileExtension',
        'md5Checksum',
        'fileSize',
        'shared',
        'user_id'
    ];

    /**
     * Views for the Dashboard timeline.
     *
     * @var string
     */
    protected static $templatePath = 'documents::backend.activities';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Transform id to hashid
     * @param $value
     * @return mixed
     */
    public function getIdAttribute($value)
    {
        return Hashids::encode($value);
    }

}
