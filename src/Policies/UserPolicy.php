<?php

namespace Iget\ApiBase\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    /**
     * Check if user is authorized to list instance users
     *
     * @param $user
     * @param $instance_id
     * @return bool
     */
    public function listInstanceUsers($user, $model, $instance_id)
    {
        return $user->belongsToInstance($instance_id);
    }

    /**
     * User can only update himself
     *
     * @param $user
     * @param $id
     * @return bool
     */
    public function update($user, $model, $id)
    {
        return ($user->id == $id);
    }

    /**
     * User can only destroy himself
     *
     * @param $user
     * @param $id
     * @return bool
     */
    public function destroy($user, $model, $id)
    {
        return ($user->id == $id);
    }

    /**
     * @param $user
     * @param $id
     * @return bool
     */
    public function show($user, $model, $id)
    {
        if (!is_null($user)) {
            if ($user->id == $id) {
                return true;
            }

            foreach ($user->instances as $userInstances) {
                if ($userInstances->users()->where('user_id', $id)->count()) {
                    return true;
                }
            }
        }

        return false;
    }
}
