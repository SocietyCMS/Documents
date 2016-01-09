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
