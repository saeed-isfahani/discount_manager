<?php

namespace Tests\Feature\Auth\Register;

use App\Models\User;
use App\Models\VerificationRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $vetificationRequest;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->vetificationRequest = VerificationRequest::factory()->create();

        // $token = JWTAuth::fromUser($user->refresh());

        // $this->withHeader('Authorization', 'Bearer' . $token);
    }

    /**
     * check the app api is active and accessible or not
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * check if mobile is used before in the user table
     */
    public function test_send_verify_should_get_error_if_mobile_exist_on_user(): void
    {
        $data = ['mobile' => $this->user->mobile];

        $response = $this->json('POST', route('register.sendVerify'), $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment([
            'message' => __('auth.errors.user_exists'),
            'data' => [],
            'errors' => []
        ]);
    }

    /**
     * check if everything ok then send verification code to mobile and return ok
     */
    public function test_send_verify_should_get_success_if_everything_be_ok(): void
    {
        $data = ['mobile' => '09366513023'];

        $response = $this->json('POST', route('register.sendVerify'), $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment([
            'message' => __('auth.messages.code_was_sent'),
            'data' => [],
            'errors' => []
        ]);
    }

    /**
     * check if everything ok then send verification code to mobile and return ok
     */
    public function test_check_verify_should_get_success_if_everything_be_ok(): void
    {
        $data['mobile'] = $this->vetificationRequest->receiver;
        $data['code'] = $this->vetificationRequest->code;

        $response = $this->json('POST', route('register.checkVerify'), $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment([
            'message' => __('auth.messages.mobile_is_just_verified'),
            'data' => [],
            'errors' => []
        ]);
    }

    /**
     * check if mobile is wrong then return badRerquest error
     */
    public function test_check_verify_should_get_badrequest_if_mobile_wrong(): void
    {
        $data['mobile'] = '09366513023';
        $data['code'] = $this->vetificationRequest->code;

        $response = $this->json('POST', route('register.checkVerify'), $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment([
            'message' => __('auth.errors.mobile_or_code_wrong_or_code_expired'),
            'data' => [],
            'errors' => []
        ]);
    }

    /**
     * check if code is wrong then return badRerquest error
     */
    public function test_check_verify_should_get_badrequest_if_code_wrong(): void
    {
        $data['mobile'] = $this->vetificationRequest->receiver;
        $data['code'] = '159753';

        $response = $this->json('POST', route('register.checkVerify'), $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment([
            'message' => __('auth.errors.mobile_or_code_wrong_or_code_expired'),
            'data' => [],
            'errors' => []
        ]);
    }

    /**
     * check if code is expired then return badRequest
     */
    public function test_check_verify_should_get_badrequest_if_code_expired(): void
    {
        $this->vetificationRequest->expire_at = now()->subMinute(5);
        $this->vetificationRequest->save();

        $data['mobile'] = $this->vetificationRequest->receiver;
        $data['code'] = $this->vetificationRequest->code;

        $response = $this->json('POST', route('register.checkVerify'), $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment([
            'message' => __('auth.errors.mobile_or_code_wrong_or_code_expired'),
            'data' => [],
            'errors' => []
        ]);
    }

    /**
     * check if code is expired then return badRequest
     */
    public function test_check_register_should_get_success_if_everything_be_ok(): void
    {
        $this->vetificationRequest->veriffication_at = now();
        $this->vetificationRequest->save();
        
        $data['first_name'] = fake()->firstName();
        $data['last_name'] = fake()->lastName();
        $data['mobile'] = $this->vetificationRequest->receiver;
        $data['code'] = $this->vetificationRequest->code;
        $data['email'] = fake()->email();

        $response = $this->json('POST', route('register'), $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment([
            'message' => __('auth.messages.user_registered_successfully'),
            'data' => ['access_token' => $response['data']['access_token']],
            'errors' => []
        ]);
    }
}
