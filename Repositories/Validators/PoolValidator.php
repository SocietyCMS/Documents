<?php

namespace Modules\Documents\Repositories\Validators;

use Modules\Core\Validators\BaseValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class PoolValidator
 * @package Modules\Documents\Repositories\Validators
 */
class PoolValidator extends BaseValidator
{

    /**
     * Specify validation rules
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title'       => 'required',
            'description' => 'min:3',
            'quota'       => 'integer',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'uid' => 'required|exists:documents__pool,uid',
            'title' => 'required',
            'description'  => 'min:3',
            'quota'=> 'integer'
        ]
    ];


}
