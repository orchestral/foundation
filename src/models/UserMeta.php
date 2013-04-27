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
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array(
		'value',
	);

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

	/**
	 * Return a meta data belong to a user.
	 * 
	 * @access public
	 * @param  string   $name
	 * @param  int      $userId
	 * @return Orchestra\Model\UserMeta
	 */
	public function scopeSearch($query, $name, $userId)
	{
		return $query->where(function ($q) 
			use ($name, $userId)
			{
				$q->where('user_id', '=', $userId)
					->where('name', '=', $name);
			});
	}

}
