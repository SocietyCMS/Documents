<?php

namespace Modules\Documents\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\Entities\EloquentHashids;
use Modules\Core\Traits\Entities\transformHashids;
use Modules\User\Traits\Activity\RecordsActivity;
use Vinkla\Hashids\Facades\Hashids;


/**
 * Class Event
 * @package Modules\Calendar\Entities
 */
class Object extends Model
{
    use RecordsActivity;
    use SoftDeletes;
    use EloquentHashids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documents__objects';

    /**
     * The fillable properties of the model.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'mimeType',
        'tag',
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
     * Get the pool that this object belongs to.
     */
    public function pool()
    {
        return $this->belongsTo('Modules\Documents\Entities\Pool', 'pool_uid', 'uid');
    }

}
