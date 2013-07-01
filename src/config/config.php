<?php

return array(

	/*
	|----------------------------------------------------------------------
	| Set handles for Orchestra Platform
	|----------------------------------------------------------------------
	*/

	'handles' => 'admin',

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

	/*
	|----------------------------------------------------------------------
	| Orchestra Platform Menu
	|----------------------------------------------------------------------
	|
	| Register Event handler to generate menu for Orchestra Platform 
	| Administration Interface.
	|
	*/

	'menu' => 'Orchestra\Services\Event\AdminMenuHandler',

);
