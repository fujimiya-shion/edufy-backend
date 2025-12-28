<?php

return [
    'bulk-actions' => [
        'delete' => [
            'label' => 'Delete',
            'action' => 'delete',
            'confirm' => [
                'title' => 'Delete selected items?',
                'message' => 'Are you sure you want to delete the selected items?',
                'buttons' => [
                    'confirm' => 'Delete',
                    'cancel' => 'Cancel',
                ],
            ],
        ],
    ],
];
