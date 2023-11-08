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
    ],
    'errors' => [
        'failed' => 'These credentials do not match our records.',
        'password' => 'The provided password is incorrect.',
        'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
        'user_exists' => 'A user with this mobile number exists',
        'code_was_sent_before' => 'A verification code was sent to you before',
    ]

];
