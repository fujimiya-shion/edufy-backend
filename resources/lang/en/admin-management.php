<?php

return [
    'form' => [
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
        'password_confirmation' => 'Confirm Password',
        'avatar' => 'Avatar',
        'roles' => 'Role',
        'active' => 'Active',
        'status' => 'Status',
        'send_reset_password_email' => 'Reset Password',
    ],
    'table' => [
        'avatar' => 'Avatar',
        'name' => 'Name',
        'email' => 'Email',
        'role' => 'Role',
        'status' => 'Status',
        'active' => 'Active',
        'inactive' => 'Inactive',
    ],
    'actions' => [
        'create' => 'Create',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'reset_password' => 'Reset Password',
        'view' => 'View',
    ],
    'action_confirms' => [
        'delete' => [
            'title' => 'Delete Admin',
            'message' => 'Are you sure you want to delete this admin?',
            'buttons' => [
                'confirm' => 'Delete',
                'cancel' => 'Cancel',
            ],
        ],
        'reset_password' => [
            'title' => 'Reset Password',
            'message' => 'Are you sure you want to reset this admin\'s password?',
            'buttons' => [
                'confirm' => 'Reset Password',
                'cancel' => 'Cancel',
            ],
        ],
    ],
    'action_successes' => [
        'delete' => 'Admin deleted successfully.',
        'reset_password' => 'Password has been reset and sent to the admin\'s email.',
    ],
    'validations' => [
        'name_required' => 'Name is required.',
        'email_invalid' => 'Email is not valid.',
        'email_required' => 'Email is required.',
        'email_unique' => 'Email must be unique.',
        'password_required' => 'Password is required.',
        'password_min' => 'Password must be at least 6 characters.',
        'password_confirmed' => 'Password confirmation does not match.',
    ],

];
