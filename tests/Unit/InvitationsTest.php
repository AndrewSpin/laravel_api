<?php

namespace Tests\Unit;

use App\Invitation;
use Tests\TestCase;

class InvitationsTest extends TestCase
{

    public function testInvitationsIndex()
    {
        $response = $this->json('get','/api/invitations',[], [
            'auth_token' => 'jc1NoH4vjaOLba8xfwbwLl4xkxanMpeVKPJ32r2fPYFverU3E3hcMfa6HNcz'
        ]);

        $result = [
            'id' => 1,
            'name' => 'user1',
            'sent_invitation' => [
                [
                    'id' => 1,
                    'title' => 't1',
                    'description' => NULL,
                    'sender_id' => 1,
                    'invited_id' => 2,
                    'status' => 0,
                    'sender' => [
                        'id' => 1,
                        'name' => 'user1',
                    ],
                    'invited' => [
                        'id' => 2,
                        'name' => 'user2',
                    ],
                ],
                [
                    'id' => 2,
                    'title' => 't2',
                    'description' => NULL,
                    'sender_id' => 1,
                    'invited_id' => 3,
                    'status' => 0,
                    'sender' => [
                        'id' => 1,
                        'name' => 'user1',
                    ],
                    'invited' => [
                        'id' => 3,
                        'name' => 'user3',
                    ],
                ],
            ],
            'received_invitation' => [
                [
                    'id' => 3,
                    'title' => 't3',
                    'description' => NULL,
                    'sender_id' => 2,
                    'invited_id' => 1,
                    'status' => 0,
                    'sender' => [
                        'id' => 2,
                        'name' => 'user2',
                    ],
                    'invited' => [
                        'id' => 1,
                        'name' => 'user1',
                    ],
                ],
                [
                    'id' => 4,
                    'title' => 't4',
                    'description' => NULL,
                    'sender_id' => 3,
                    'invited_id' => 1,
                    'status' => 0,
                    'sender' => [
                        'id' => 3,
                        'name' => 'user3',
                    ],
                    'invited' => [
                        'id' => 1,
                        'name' => 'user1',
                    ],
                ],
            ],
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($result);
    }

    public function testInvitationsSent()
    {
        $response = $this->json('get','/api/invitations/sent',[], [
            'auth_token' => 'jc1NoH4vjaOLba8xfwbwLl4xkxanMpeVKPJ32r2fPYFverU3E3hcMfa6HNcz'
        ]);

        $result = [
            'id' => 1,
            'name' => 'user1',
            'sent_invitation' => [
                [
                    'id' => 1,
                    'title' => 't1',
                    'description' => NULL,
                    'sender_id' => 1,
                    'invited_id' => 2,
                    'status' => 0,
                    'sender' => [
                        'id' => 1,
                        'name' => 'user1',
                    ],
                    'invited' => [
                        'id' => 2,
                        'name' => 'user2',
                    ],
                ],
                [
                    'id' => 2,
                    'title' => 't2',
                    'description' => NULL,
                    'sender_id' => 1,
                    'invited_id' => 3,
                    'status' => 0,
                    'sender' => [
                        'id' => 1,
                        'name' => 'user1',
                    ],
                    'invited' => [
                        'id' => 3,
                        'name' => 'user3',
                    ],
                ],
            ],
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($result);
    }

    public function testInvitationsReceived()
    {
        $response = $this->json('get','/api/invitations/received',[], [
            'auth_token' => 'jc1NoH4vjaOLba8xfwbwLl4xkxanMpeVKPJ32r2fPYFverU3E3hcMfa6HNcz'
        ]);

        $result = [
            'id' => 1,
            'name' => 'user1',
            'received_invitation' => [
                [
                    'id' => 3,
                    'title' => 't3',
                    'description' => NULL,
                    'sender_id' => 2,
                    'invited_id' => 1,
                    'status' => 0,
                    'sender' => [
                        'id' => 2,
                        'name' => 'user2',
                    ],
                    'invited' => [
                        'id' => 1,
                        'name' => 'user1',
                    ],
                ],
                [
                    'id' => 4,
                    'title' => 't4',
                    'description' => NULL,
                    'sender_id' => 3,
                    'invited_id' => 1,
                    'status' => 0,
                    'sender' => [
                        'id' => 3,
                        'name' => 'user3',
                    ],
                    'invited' => [
                        'id' => 1,
                        'name' => 'user1',
                    ],
                ],
            ],
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($result);
    }

    public function testInvitationsSand()
    {
        $response = $this->json('put', '/api/invitations/sand', [
            'title' => 'apiTest',
            'invited_id' => 4
        ], ['auth_token' => 'jc1NoH4vjaOLba8xfwbwLl4xkxanMpeVKPJ32r2fPYFverU3E3hcMfa6HNcz']);

        $result = [
            'title' => 'apiTest',
            'description' => NULL,
            'sender_id' => 2,
            'invited_id' => 4,
            'status' => 0,
            'sender' => [
                'id' => 2,
                'name' => 'user2',
            ],
            'invited' => [
                'id' => 4,
                'name' => 'user4',
            ],
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($result);

        Invitation::where('title', 'apiTest')->delete();
    }

    public function testInvitationsCancel()
    {
        $testInvitation = new Invitation();

        $testInvitation->setAttribute('title', 'forTest');
        $testInvitation->setAttribute('sender_id', 1);
        $testInvitation->setAttribute('invited_id', 2);
        $testInvitation->save();


        $response = $this->json('get','/api/invitations/cancel/'.$testInvitation->id, [], [
            'auth_token' => 'jc1NoH4vjaOLba8xfwbwLl4xkxanMpeVKPJ32r2fPYFverU3E3hcMfa6HNcz'
        ]);

        $response->assertStatus(200);
    }

    public function testInvitationsAccept()
    {
        $response = $this->json('get','/api/invitations/accept/3',[], [
            'auth_token' => 'jc1NoH4vjaOLba8xfwbwLl4xkxanMpeVKPJ32r2fPYFverU3E3hcMfa6HNcz'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => Invitation::STATUS_ACCEPT]);

        Invitation::find(3)->updateStatus(Invitation::STATUS_PENDING);
    }

    public function testInvitationsDecline()
    {
        $response = $this->json('get','/api/invitations/decline/3',[], [
            'auth_token' => 'jc1NoH4vjaOLba8xfwbwLl4xkxanMpeVKPJ32r2fPYFverU3E3hcMfa6HNcz'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => Invitation::STATUS_DECLINE]);

        Invitation::find(3)->updateStatus(Invitation::STATUS_PENDING);
    }
}
