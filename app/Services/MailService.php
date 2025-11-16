<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailService
{
    /**
     * Send email with SSL verification disabled for certificate mismatch
     */
    public static function sendWithoutSSLVerification($to, $mailable)
    {
        // Just use the regular Mail facade - SSL verification is handled in AppServiceProvider
        Mail::to($to)->send($mailable);
    }
}

