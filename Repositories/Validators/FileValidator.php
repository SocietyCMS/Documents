<?php

namespace Modules\Documents\Repositories\Validators;

use Modules\Core\Validators\BaseValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class PoolValidator
 * @package Modules\Documents\Repositories\Validators
 */
class FileValidator extends BaseValidator
{

    /**
     * Specify validation rules
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title'       => 'string',
            'description' => 'min:3',
            'shared'      => 'boolean',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title'       => 'required|min:3|string',
            'description' => 'min:3',
            'shared'      => 'boolean',
        ],
    ];


}
