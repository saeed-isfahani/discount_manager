<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'enumes' => [],
    'validations' => [],
    'messages' => [
        'your_verification_code' => 'Your verification code is: :code',
        'code_was_sent' => 'Code sent to you successfully',
        'mobile_is_just_verified' => 'Mobile just verified successfully',
        'user_registered_successfully' => 'User is registered successfully',
        'profile_updated_successfully' => 'Your profile updated successfully',
        'user_logged_out_successfully' => 'User logged out successfully',
    ],
    'errors' => [
        'failed' => 'These credentials do not match our records.',
        'password' => 'The provided password is incorrect.',
        'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
        'user_exists' => 'A user with this mobile number exists',
        'code_was_sent_before' => 'A verification code was sent to you before',
        'mobile_or_code_wrong_or_code_expired' => 'Mobile or code is wrong or code expired',
        'mobile_format_is_not_valid' => 'Your mobile format is not valid',
        'user_is_not_login' => 'User is not login',
    ]

];
