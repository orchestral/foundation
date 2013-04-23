<?php

return array(
	'process'       => 'Installation Process',
	'hide-password' => 'Database password is hidden for security.',
	'verify'        => 'Please ensure following configuration is correct based on your :filename.',
	'solution'      => 'Solution',

	'status'     => array(
		'still' => 'Still Workable',
		'work'  => 'Workable',
		'not'   => 'Not Workable',
	),

	'connection' => array(
		'status'  => 'Connection Status',
		'success' => 'Successful',
		'fail'    => 'Failed',
	),
	
	'auth'     => array(
		'title'       => 'Authentication Setting',
		'driver'      => 'Driver',
		'model'       => 'Model',
		'requirement' => array(
			'driver'     => 'Orchestra only work with Eloquent Driver for Auth',
			'instanceof' => 'Model name should be an instance of :class',
		),
	),

	'database' => array(
		'title'    => 'Database Setting',
		'host'     => 'Host',
		'name'     => 'Database Name',
		'password' => 'Password',
		'username' => 'Username',
		'type'     => 'Database Type',
	),

	'steps'    => array(
		'requirement' => 'Check Requirements',
		'account'     => 'Create Administrator Account',
		'application' => 'Application Information',
		'done'        => 'Done',
	),

	'system'   => array(
		'title'       => 'System Requirement',
		'description' => 'Please ensure the following requirement is profilled before installing Orchestra Platform.',
		'requirement' => 'Requirement',
		'status'      => 'Status',

		'writableStorage' => array(
			'name' => "Writable to :path",
			'solution' => "Change the directory permission to 0777, however it might cause a security issue if this folder is accessible from the web.",
		),
		'writableAsset' => array(
			'name'     => "Writable to :path",
			'solution' => "Change the directory permission to 0777. Once installation is completed, please revert the permission to 0755.",
		),
	),
);