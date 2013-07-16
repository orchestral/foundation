<?php namespace Orchestra\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Config;

class Role extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roles';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array(
		'name',
	);

	/**
	 * Indicates if the model should soft delete.
	 *
	 * @var bool
	 */
	protected $softDelete = true;

	/**
	 * Has many and belongs to relationship with User.
	 *
	 * @access public
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users() 
	{
		return $this->belongsToMany('\Orchestra\Model\User', 'user_role')->withTimestamps();
	}

	/**
	 * Get default roles for Orchestra Platform
	 *
	 * @static
	 * @access public
	 * @return self
	 */
	public static function admin()
	{
		return static::find(
			Config::get('orchestra/foundation::roles.admin')
		);
	}

	/**
	 * Get default member roles for Orchestra Platform
	 *
	 * @static
	 * @access public
	 * @return self
	 */
	public static function member()
	{
		return static::find(
			Config::get('orchestra/foundation::roles.member')
		);
	}
}
