<?php

return [
    'safe-mode' => 'Orchestra Platform is running on safe mode.',

    'account' => [
        'password' => [
            'invalid' => 'Current password does not match our record, please try again.',
            'update' => 'Your password has been updated.',
        ],
        'profile' => [
            'update' => 'Your profile has been updated.',
        ],
    ],

    'credential' => [
        'invalid-combination' => 'Invalid user and password combination.',
        'logged-in' => 'You have been logged in.',
        'logged-out' => 'You have been logged out.',
        'unauthorized' => 'You are not authorized to access this action.',
        'register' => [
            'email-fail' => 'Unable to send User Registration Confirmation e-mail.',
            'email-send' => 'User Registration Confirmation e-mail has been sent to your inbox.',
            'existing-user' => 'This e-mail address is already associated with another user.',
        ],
    ],

    'db-failed' => 'Unable to save to database',
    'db-404' => 'Requested data is not available on the database',

    'extensions' => [
        'activate' => 'Extension :name activated',
        'deactivate' => 'Extension :name deactivated',
        'configure' => 'Configuration for Extension :name has been updated',
        'migrate' => 'Extension :name has been updated',
        'depends-on' => 'Extension :name was not activated because depends on :dependencies',
        'other-depends-on' => 'Extension :name was not deactivated because :dependencies depends on it',
    ],

    'settings' => [
        'update' => 'Application settings has been updated',
        'system-update' => 'Orchestra Foundation has been updated',
    ],

    'users' => [
        'create' => 'User has been created',
        'update' => 'User has been updated',
        'delete' => 'User has been deleted',
    ],
];
