<?php

namespace App\Http\Controllers;

use App\Invitation;
use Illuminate\Http\Request;
use App\User;

class InvitationController extends Controller
{
    /**
     * @return Invitation[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        return User::getInvitations($request->header('auth_token'), ['sent_invitation','received_invitation']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function sent(Request $request)
    {
        return User::getInvitations($request->header('auth_token'), ['sent_invitation']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function received(Request $request)
    {
        return User::getInvitations($request->header('auth_token'), ['received_invitation']);
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function sand(Request $request)
    {
        $invitation = new Invitation();

        $invitation->fill($request->all());
        $invitation->setAttribute('sender_id', 2);

        if(!($invitation->validate() && $invitation->save()))
            return $invitation->errors;

        return Invitation::selectWithUsers()
            ->where('id', '=', $invitation->id)
            ->first();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|string|null
     */
    public function accept(Request $request, $id)
    {
        return $this->InvitationUpdateStatus($request, $id,Invitation::STATUS_ACCEPT);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|string|null
     */
    public function decline(Request $request, $id)
    {
        return $this->InvitationUpdateStatus($request, $id,Invitation::STATUS_DECLINE);
    }

    /**
     * @param Request $request
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function cancel(Request $request, $id)
    {
        $invitation = $this->findInvitationByIdAndUserType($id, $request->header('auth_token'), 'sender_id');

        if(!$invitation)
            return 'Invitation is not found';

        if($invitation->delete())
            return 'Invitation was deleted';
    }

    /**
     * @param Request $request
     * @param $id
     * @param $status
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|string|null
     */
    protected function InvitationUpdateStatus(Request $request, $id, $status)
    {
        $invitation = $this->findInvitationByIdAndUserType($id, $request->header('auth_token'), 'invited_id');

        if(!$invitation)
            return 'Invitation is not found';

        $invitation->updateStatus($status);

        return Invitation::selectWithUsers()
            ->where('id', $invitation->id)
            ->first();
    }

    /**
     * @param $id integer
     * @param $token string, auth_token of User
     * @param $type string
     * @return Invitation|null
     */
    protected function findInvitationByIdAndUserType($id, $token, $type)
    {
        return Invitation::where('id',$id)
            ->where($type, User::findByAuthToken($token)->id)
            ->first();
    }
}
