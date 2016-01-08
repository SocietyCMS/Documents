<?php

namespace Modules\Documents\Repositories;

use Modules\Core\Repositories\Eloquent\EloquentHashidsRepository;
use Prettus\Validator\Contracts\ValidatorInterface;

class ObjectRepository extends EloquentHashidsRepository
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title' => 'required',
            'text'  => 'min:3',
            'author'=> 'required'
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title' => 'required'
        ]
    ];


    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return 'Modules\\Documents\\Entities\\Object';
    }
}
