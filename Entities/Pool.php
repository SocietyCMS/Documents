<?php

namespace Modules\Documents\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\Entities\EloquentHashids;
use Modules\Core\Traits\Entities\transformHashids;


/**
 * Class Event
 * @package Modules\Calendar\Entities
 */
class Pool extends Model
{
    use SoftDeletes;
    use EloquentHashids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documents__pool';

    /**
     * The fillable properties of the model.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'quota'
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];


    /**
     * Get the files in this pool.
     */
    public function files()
    {
        return $this->hasMany('Modules\Documents\Entities\Object', 'pool_uid', 'uid');
    }

}
