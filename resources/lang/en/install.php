<?php

return [
    'process'       => 'Installation Process',
    'hide-password' => 'Database password is hidden for security.',
    'verify'        => 'Please ensure following configuration is correct based on your :filename.',
    'solution'      => 'Solution',

    'status'     => [
        'still' => 'Still Workable',
        'work'  => 'Workable',
        'not'   => 'Not Workable',
    ],

    'connection' => [
        'status'  => 'Connection Status',
        'success' => 'Successful',
        'fail'    => 'Failed',
    ],

    'auth'     => [
        'title'       => 'Authentication Setting',
        'driver'      => 'Driver',
        'model'       => 'Model',
        'requirement' => [
            'driver'     => 'Orchestra Platform require Auth using the Eloquent Driver',
            'instanceof' => 'Model name should be an instance of :class',
        ],
    ],

    'database' => [
        'title'    => 'Database Setting',
        'host'     => 'Host',
        'name'     => 'Database Name',
        'password' => 'Password',
        'username' => 'Username',
        'type'     => 'Database Type',
    ],

    'steps'    => [
        'requirement' => 'Check Requirements',
        'account'     => 'Create Administrator',
        'application' => 'Application Information',
        'done'        => 'Done',
    ],

    'system'   => [
        'title'       => 'System Requirement',
        'description' => 'Please ensure the following requirement is profilled before installing Orchestra Platform.',
        'requirement' => 'Requirement',
        'status'      => 'Status',

        'writableStorage' => [
            'name'     => "Writable to :path",
            'solution' => "Change the directory permission to 0777, however it might cause a security issue if this folder is accessible from the web.",
        ],
        'writableAsset' => [
            'name'     => "Writable to :path",
            'solution' => "Change the directory permission to 0777. Once installation is completed, please revert the permission to 0755.",
        ],
    ],

    'user' => [
        'created'   => 'User created, you can now login to the administation page',
        'duplicate' => 'Unable to install when there already user registered.',
    ],
];
