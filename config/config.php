<?php

return [

    /*
    |----------------------------------------------------------------------
    | Set handles for Orchestra Platform
    |----------------------------------------------------------------------
    |
    | By default we would assign "admin" as the default URI, which mean you
    | can access it from http://localhost/admin. However you can also set
    | the default to "//admin.acme.com/" and Orchestra Platform would now
    | handle to the subdomain instead.
    |
    */

    'handles' => 'admin',

    /*
    |----------------------------------------------------------------------
    | Route configuration for Orchestra Platform
    |----------------------------------------------------------------------
    */

    'routes' => [

        /*
        |------------------------------------------------------------------
        | Default Reset Password Route
        |------------------------------------------------------------------
        */

        'reset' => 'orchestra::forgot/reset',

        /*
        |------------------------------------------------------------------
        | Default Guest Route
        |------------------------------------------------------------------
        */

        'guest' => 'orchestra::login',

        /*
        |------------------------------------------------------------------
        | Default User Route
        |------------------------------------------------------------------
        */

        'user' => 'orchestra::/',

    ],

    /*
    |----------------------------------------------------------------------
    | Roles configuration for Orchestra Platform
    |----------------------------------------------------------------------
    */

    'roles' => [

        /*
        |------------------------------------------------------------------
        | Default Role
        |------------------------------------------------------------------
        |
        | The default role can't be deleted at any cause and would always
        | have Orchestra `manage-user` and `manage-orchestra` actions.
        |
        */

        'admin' => 1,

        /*
        |------------------------------------------------------------------
        | Default Member Role
        |------------------------------------------------------------------
        |
        | The default member role.
        |
        */

        'member' => 2,

    ],


    /*
    |----------------------------------------------------------------------
    | ACL actions configuration for Orchestra Platform
    |----------------------------------------------------------------------
    */

    'actions' => [
        'Manage Users', 'Manage Orchestra', 'Manage Roles', 'Manage Acl',
    ],

    /*
    |----------------------------------------------------------------------
    | Login Throttles for Orchestra Platform
    |----------------------------------------------------------------------
    */

    'throttle' => [

        /*
        |------------------------------------------------------------------
        | Default Resolver
        |------------------------------------------------------------------
        |
        | The default for handling login throttles, by default we use the
        | default basic throttle `Orchestra\Foundation\Auth\Throttle\Basic`
        | which is identical Laravel offering.
        |
        | However you can disable it by changing to without throttling
        | `Orchestra\Foundation\Auth\Throttle\Without`.
        |
        */

        'resolver' => Orchestra\Foundation\Auth\Throttle\Basic::class,

        /*
        |------------------------------------------------------------------
        | Max attempts
        |------------------------------------------------------------------
        |
        | Define the max attempts allowed before authentication is disabled
        | for the given user.
        |
        */

        'attempts' => 5,

        /*
        |------------------------------------------------------------------
        | Locked for (in seconds)
        |------------------------------------------------------------------
        |
        | Define number of seconds for the login throttles to disabled
        | user authentication after exceeding max attempts.
        |
        */

        'locked_for' => 60,
    ],
];
