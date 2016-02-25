<?php

namespace Modules\Documents\Installer;

class RegisterDefaultPermissions
{

    public $defaultPermissions = [

        'access-documents' => [
            'display_name' => 'documents::module-permissions.access-documents.display_name',
            'description' => 'documents::module-permissions.access-documents.description'
        ],

        'manage-pools' => [
            'display_name' => 'documents::module-permissions.manage-pools.display_name',
            'description' => 'documents::module-permissions.manage-pools.description'
        ],
    ];
}
