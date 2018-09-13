<?php

return [
    'role_structure' => [
        'administrador' => [
            'user' => 'c,r,u,d',
            'role' => 'c,r,u,d',
            'mailbox' => 'c,r,u,d',
            'alias' => 'c,r,u,d',
            'domain' => 'c,r,u,d',
            'role' => 'c,r,u,d',
            'domain' => 'c,r,u,d',
        ],
        'usuario' => [
            'mailbox' => 'c,r,u',
            'alias' => 'c,r,u',
            'domain' => 'r',
        ],
    ],
    'permissions_map' => [
        'c' => 'cadastrar',
        'r' => 'listar',
        'u' => 'alterar',
        'd' => 'excluir'
    ]
];
