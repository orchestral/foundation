<?php namespace Orchestra\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserMeta extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_meta';

	/**
	 * Belongs to relationship with User.
	 *
	 * @access public
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function users()
	{
		return $this->belongsTo('\Orchestra\Model\User', 'user_id');
	}

}