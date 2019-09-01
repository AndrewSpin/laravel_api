<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'auth_token',
    ];


    public function sent_invitation()
    {
        return $this->hasMany('App\Invitation', 'sender_id')->with('sender')->with('invited');
    }

    public function received_invitation()
    {
        return $this->hasMany('App\Invitation', 'invited_id')->with('sender')->with('invited');
    }

    /**
     * @param string $auth_token
     * @param array $relations
     * @return mixed
     */
    public static function getInvitations($auth_token ='', array $relations = ['sent_invitation', 'received_invitation'])
    {
        $query = self::select('id', 'name');

        foreach ($relations as $relation){
            $query->with($relation);
        }

        return $query->where('auth_token', $auth_token)->first();
    }

    /**
     * @param $token
     * @return User
     */
    public static function findByAuthToken($token)
    {
        return (self::where('auth_token', $token)->first()) ? : new User();
    }


}
