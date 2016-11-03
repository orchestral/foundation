<?php

return [
    'register' => [
        'title' => 'Your New Account',
        'message' => [
            'intro' => 'Thank you for registering with us, in order to login please use the following:',
            'email' => 'E-mail Address: :email',
            'password' => 'Password: :password',
        ],
    ],
    'forgot'     => [
        'title' => 'Reset Your Password',
        'message' => [
            'intro' => 'You are receiving this email because we received a password reset request for your account. Click the button below to reset your password:',
            'expired_in' => 'This link will expire in :expired minutes.',
            'outro'  => 'If you did not request a password reset, no further action is required.',
        ],
    ],
];
