<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Response;


class LoginTest extends TestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_check_verify_should_get_found_if_post_is_empty()
    {
        $response = $this->post('api/v1/auth/login/check-verify');
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_send_verify_should_get_found_if_post_is_empty()
    {
        $response = $this->post('api/v1/auth/login/send-verify');
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_check_verify_should_get_found_if_mobile_is_empty()
    {
        $response = $this->post('api/v1/auth/login/check-verify', [
            'mobile' => null,
        ]);
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_send_verify_should_get_found_if_mobile_or_code_is_empty()
    {
        $response = $this->post('api/v1/auth/login/send-verify', [
            'mobile' => null,
            'code' => null,
        ]);
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_login()
    {
        $response = $this->post('api/v1/auth/login/send-verify', [
            'mobile' => $this->user->mobile,
        ]);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'message' => __('auth.messages.code_was_sent'),
                'errors' => [],
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'access_token'
                    ],
                ],
            ]);

        $lastVerificationRequest = VerificationRequest::latestValidLoginRequestByMobile($this->user->mobile)->first();

        $response = $this->post('api/v1/auth/login/check-verify', [
            'mobile' => $this->user->mobile,
            'code' => $lastVerificationRequest->code,
        ]);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'message' => __('auth.messages.you_have_successfully_logged_into_your_account'),
                'errors' => [],
            ]);
    }
}
