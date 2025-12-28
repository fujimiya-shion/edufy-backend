<?php

return [
    'bulk-actions' => [
        'delete' => [
            'label' => 'Xóa',
            'action' => 'delete',
            'confirm' => [
                'title' => 'Xóa mục đã chọn?',
                'message' => 'Bạn có chắc chắn muốn xóa các mục đã chọn không?',
                'buttons' => [
                    'confirm' => 'Xóa',
                    'cancel' => 'Hủy',
                ],
            ],
        ],
    ],
];
