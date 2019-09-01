<?php

namespace App;

use App\Http\Requests\InvitationsRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Invitation extends Model
{
    const STATUS_ACCEPT = 1;
    const STATUS_DECLINE = -1;
    const STATUS_PENDING = 0;

    protected $fillable = ['title', 'description', 'sender_id', 'invited_id', 'status'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public $errors;

    public function validate()
    {
        $validator = Validator::make($this->attributesToArray(),(new InvitationsRequest())->rules());

        if($validator->fails()){
            $this->errors = $validator->errors();
            return false;
        }else
            return true;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo('App\User')->select('id', 'name');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invited()
    {
        return $this->belongsTo('App\User')->select('id', 'name');
    }

    /**
     * @return Builder;
     */
    public static function selectWithUsers()
    {
        return self::select()
            ->with('sender')
            ->with('invited');
    }

    /**
     * @param int $status
     * @return mixed
     */
    public function updateStatus(int $status)
    {
        return $this->setAttribute('status', $status)->update();
    }
}
