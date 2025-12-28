<?php

return [
    'form' => [
        'display_name' => 'Name',
        'name' => 'Role Code',
        'description' => 'Description',
        'select_all_permissions' => 'Select All Permissions',

    ],
    'table' => [
        'name' => 'Name',
        'role_code' => 'Role Code',
        'description' => 'Description',
    ],
    'actions' => [
        'create' => 'Create',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'view' => 'View',
    ],
    'validations' => [
        'name_required' => 'Name is required.',
        'role_code_required' => 'Role Code is required.',
        'role_code_unique' => 'Role Code must be unique.',
        'description_required' => 'Description is required.',
    ],
];
