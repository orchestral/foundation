<?php namespace Orchestra\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Role extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roles';

	/**
	 * Has many and belongs to relationship with User.
	 *
	 * @access public
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users() 
	{
		return $this->belongsToMany('\Orchestra\Model\User', 'user_role');
	}
}
