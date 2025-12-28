<?php

return [
    'form' => [
        'display_name' => 'Tên',
        'name' => 'Mã vai trò',
        'description' => 'Mô tả',
        'select_all_permissions' => 'Chọn tất cả quyền',

    ],
    'table' => [
        'name' => 'Tên',
        'role_code' => 'Mã vai trò',
        'description' => 'Mô tả',
    ],
    'actions' => [
        'create' => 'Tạo mới',
        'edit' => 'Chỉnh sửa',
        'delete' => 'Xóa',
        'view' => 'Xem',
    ],
    'validations' => [
        'name_required' => 'Tên không được để trống.',
        'name_max_length' => 'Tên không được vượt quá 255 ký tự.',
        'role_code_required' => 'Mã vai trò không được để trống.',
        'role_code_unique' => 'Mã vai trò phải là duy nhất.',
        'description_max_length' => 'Mô tả không được vượt quá 1000 ký tự.',
    ],
    'action_confirms' => [
        'delete' => [
            'title' => 'Xóa vai trò',
            'message' => 'Bạn có chắc chắn muốn xóa vai trò này?',
            'buttons' => [
                'confirm' => 'Xóa',
                'cancel' => 'Hủy',
            ],
        ],
    ],
];
