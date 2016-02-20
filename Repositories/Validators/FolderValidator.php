<?php

namespace Modules\Documents\Repositories\Validators;

use Modules\Core\Validators\BaseValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class PoolValidator
 * @package Modules\Documents\Repositories\Validators
 */
class FolderValidator extends BaseValidator
{


    /**
     * Sanitize input with special rules
     *
     * @param $request
     * @return mixed
     */
    public function sanitize($request)
    {
        if(empty($request['parent_uid']) || $request['parent_uid'] == 'null'){
            $request['parent_uid'] = null;
        }

        return $request;
    }

    /**
     * Specify validation rules
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title'       => 'required',
            'description' => 'min:3',
            'parent_uid'  => 'exists:documents__objects,uid',
            'shared'      => 'boolean',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title'       => 'required',
            'description' => 'min:3',
            'parent_uid'  => 'exists:documents__objects,uid',
            'shared'      => 'boolean',
        ],
    ];


}
