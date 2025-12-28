<?php

return [
    'form' => [
        'name' => 'Tên',
        'email' => 'Email',
        'password' => 'Mật khẩu',
        'password_confirmation' => 'Xác nhận mật khẩu',
        'avatar' => 'Ảnh đại diện',
        'roles' => 'Vai trò',
        'active' => 'Hoạt động',
        'status' => 'Trạng thái',
        'send_reset_password_email' => 'Đặt lại mật khẩu',
    ],
    'table' => [
        'avatar' => 'Ảnh đại diện',
        'name' => 'Tên',
        'email' => 'Email',
        'role' => 'Vai trò',
        'status' => 'Trạng thái',
        'active' => 'Hoạt động',
        'inactive' => 'Không hoạt động',
    ],
    'actions' => [
        'create' => 'Tạo mới',
        'edit' => 'Chỉnh sửa',
        'delete' => 'Xóa',
        'reset_password' => 'Đặt lại mật khẩu',
        'view' => 'Xem',
    ],
    'action_confirms' => [
        'delete' => [
            'title' => 'Xóa quản trị viên',
            'message' => 'Bạn có chắc chắn muốn xóa quản trị viên này?',
            'buttons' => [
                'confirm' => 'Xóa',
                'cancel' => 'Hủy bỏ',
            ],
        ],
        'reset_password' => [
            'title' => 'Đặt lại mật khẩu',
            'message' => 'Bạn có chắc chắn muốn đặt lại mật khẩu của quản trị viên này?',
            'buttons' => [
                'confirm' => 'Đặt lại mật khẩu',
                'cancel' => 'Hủy bỏ',
            ],
        ],
    ],
    'action_successes' => [
        'delete' => 'Đã xóa quản trị viên thành công.',
        'reset_password' => 'Đã đặt lại mật khẩu thành công.',
    ],
    'validations' => [
        'name_required' => 'Tên là bắt buộc.',
        'email_invalid' => 'Email không hợp lệ.',
        'email_required' => 'Email là bắt buộc.',
        'email_unique' => 'Email phải là duy nhất.',
        'password_required' => 'Mật khẩu là bắt buộc.',
        'password_min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        'password_confirmed' => 'Xác nhận mật khẩu không khớp.',
    ],

];
