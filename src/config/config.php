<?php

return array(

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

    'routes' => array(

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

        'user'  => 'orchestra::/',

    ),


    /*
    |----------------------------------------------------------------------
    | Roles configuration for Orchestra Platform
    |----------------------------------------------------------------------
    */

    'roles' => array(

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

    ),

);
