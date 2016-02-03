<?php

namespace Iget\ApiBase\Models;

use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends \Eloquent implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function instances()
    {
        return $this->belongsToMany(Instance::class, 'instance_users');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function owned_instances()
    {
        return $this->hasMany(Instance::class, 'owner_user_id');
    }

    /**
     * @param $instance_id
     * @return bool
     */
    public function belongsToInstance($instance_id)
    {
        return (bool) User::instances()->where('instance_id', '=', $instance_id)->count();
    }

    /**
     * @param $instance_id
     * @return bool
     */
    public function ownsInstance($instance_id)
    {
        return (bool) User::instances()->where('instance_id', '=', $instance_id)->where('owner', '=', 1)->count();
    }

    /**
     * @param $query
     * @param $instance_id
     * @return mixed
     */
    public function scopeFromInstance($query, $instance_id)
    {
        return $query->selectRaw('users.*')->join('instance_users', function($join) use ($instance_id) {
            $join->on('users.id', '=', 'instance_users.user_id')
                ->where('instance_users.instance_id', '=', $instance_id);
        })->havingRaw('count(instance_users.id) > 0')->groupBy('users.id');
    }

    /**
     * Hash the password field
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Check if user has super-admin privileges
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return true; // @todo: Implement this
    }
}
