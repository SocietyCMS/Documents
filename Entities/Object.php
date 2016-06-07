<?php

namespace Modules\Documents\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Core\Traits\Entities\EloquentHashids;
use Modules\Core\Traits\Activity\RecordsActivity;


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
        'description',
        'shared',
        'user_id',
        'parent_uid',
        'pool_uid'
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
     * @var array
     */
    protected static $recordEvents = ['created'];

    /**
     * Privacy setting for the dashboard. Only show Documents to logged in users.
     *
     * @var string
     */
    protected static $activityPrivacy = 'protected';

    /**
     * Get the pool that this object belongs to.
     */
    public function getFQPath()
    {
        if ($this->parent == null) {
            return $this->getObjectName();
        }
        return $this->parent->getFQPath().'/'.$this->getObjectName();
    }

    /**
     * Get the pool that this object belongs to.
     */
    public function getNSPath()
    {
        if ($this->parent == null) {
            return $this->title;
        }
        return $this->parent->getNSPath().'/'.$this->title;
    }

    /**
     * Get the pool that this object belongs to.
     */
    public function getFQUid()
    {
        if ($this->parent == null) {
            return $this->uid;
        }
        return $this->parent->getFQUid().':'.$this->uid;
    }

    /**
     * Get the pool that this object belongs to.
     */
    public function getObjectName()
    {
        if ($this->fileExtension) {
            return Str::slug($this->title).'.'.$this->fileExtension;
        }

        return Str::slug($this->title);
    }


    /**
     * Get the parent of this object.
     */
    public function parent()
    {
        return $this->belongsTo('Modules\Documents\Entities\Object', 'parent_uid', 'uid');
    }

    /**
     * Get the pool that this object belongs to.
     */
    public function pool()
    {
        return $this->belongsTo('Modules\Documents\Entities\Pool', 'pool_uid', 'uid');
    }

}
