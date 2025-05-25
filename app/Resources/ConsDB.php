<?php

namespace App\Resources;

class ConsDB {
    // Access Right List
    const ADMIN_AR        = '1';
    const ACCESS_RIGHT_AR = '2';
    const MATERIAL_AR     = '3';
    const WORKORDER_AR    = '4';

    // Access Right List For View
    const ACCESS_RIGHT_LIST = [
        [
            'code'   => self::ADMIN_AR,
            'name'   => 'Admin',
            'create' => true,
            'read'   => true,
            'update' => true,
            'delete' => true,
        ],
        [
            'code'   => self::ACCESS_RIGHT_AR,
            'name'   => 'Access Right',
            'create' => true,
            'read'   => true,
            'update' => true,
            'delete' => true,
        ],
        [
            'code'   => self::MATERIAL_AR,
            'name'   => 'Material',
            'create' => true,
            'read'   => true,
            'update' => true,
            'delete' => true,
        ],
        [
            'code'   => self::WORKORDER_AR,
            'name'   => 'Workorder',
            'create' => false,
            'read'   => true,
            'update' => true,
            'delete' => false,
        ]
    ];
}