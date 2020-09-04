<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails {
        sendResetLinkEmail as sendResetLink;
    }

    public function sendResetLinkEmail(Request $request)
    {
        /**
         * Override Methods for always positive response,so hackers can check which email is registered
         */
        try {
            return $this->sendResetLink($request);
        } catch (ValidationException $e) {
            return $this->sendResetLinkResponse($request, Password::RESET_LINK_SENT);
        }
    }
}
