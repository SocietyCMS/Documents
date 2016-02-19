<?php

namespace Modules\Documents\Installer;

class RegisterDefaultPermissions
{

    public $defaultPermissions = [

        'manage-pools' => [
            'display_name' => 'documents::module-permissions.manage-pools.display_name',
            'description' => 'documents::module-permissions.manage-pools.description'
        ],
    ];
}
