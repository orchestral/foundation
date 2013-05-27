<?php

return array(
	'account' => array(
		'password' => array(
			'invalid' => 'Current password does not match our record, please try again',
			'update'  => 'Your password has been updated',
		),
		'profile' => array(
			'update' => 'Your profile has been updated',
		),

	),

	'credential' => array(
		'invalid-combination' => 'Invalid user and password combination',
		'logged-in'           => 'You have been logged in',
		'logged-out'          => 'You have been logged out',
		'unauthorized'        => 'You are not authorized to access this action',
		'register'            => array(
			'email-fail'    => 'Unable to send User Registration Confirmation E-mail',
			'email-send'    => 'User Registration Confirmation E-mail has been sent, please check your inbox',
			'existing-user' => 'This e-mail address is already associated with another user',
		),
	),

	'db-failed' => 'Unable to save to database',
	'db-404'    => 'Requested data is not available on the database',

	'extensions' => array(
		'activate'         => 'Extension :name activated',
		'deactivate'       => 'Extension :name deactivated',
		'configure'        => 'Configuration for Extension :name has been updated',
		'update'           => 'Extension :name has been updated',
		'depends-on'       => 'Extension :name was not activated because depends on :dependencies',
		'other-depends-on' => 'Extension :name was not deactivated because :dependencies depends on it',
	),

	'reminders' => array(
		'email-fail' => 'Unable to send Reset Password E-mail',
		'email-send' => 'Reset Password E-mail has been sent, please check your inbox',
	),

	'settings' => array(
		'update'  => 'Application settings has been updated',
	),

	'users' => array(
		'create' => 'User has been created',
		'update' => 'User has been updated',
		'delete' => 'User has been deleted',
	),
);
