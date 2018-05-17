<?php

return [
    'role_structure' => [
        'administrador' => [
            'users' => 'c,r,u,d',
            'acl' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'usuario' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ]
    ],
    'permission_structure' => [
        'cru_user' => [
            'profile' => 'c,r,u'
        ],
    ],
    'permissions_map' => [
        'c' => 'cadastrar',
        'r' => 'listar',
        'u' => 'editar',
        'd' => 'excluir' 
    ]
];
