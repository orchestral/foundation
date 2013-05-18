<?php namespace Orchestra\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Config;
use Orchestra\Acl;

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
		return $this->belongsToMany('\Orchestra\Model\User', 'user_role');
	}

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::creating(function ($role)
		{
			Acl::addRole($role->getAttribute('name'));
		});

		static::updating(function ($role)
		{
			$originalName = $role->getOriginal('name');
			$currentName  = $role->getAttribute('name');
			$deletedAt    = $role->getDeletedAtColumn();

			if ($role->isSoftDeleting() 
				and is_null($role->getAttribute($deletedAt)) 
				and ! is_null($role->getOriginal($deletedAt)))
			{
				Acl::addRole($currentName);
			}
			else 
			{
				Acl::renameRole($originalName, $currentName);
			}
		});

		static::deleting(function ($role)
		{
			Acl::removeRole($role->getAttribute('name'));
		});
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
			Config::get('orchestra/auth::role.admin')
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
			Config::get('orchestra/auth::role.member')
		);
	}
}
