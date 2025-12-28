<?php

namespace App\Jobs;

use App\Mail\ResetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function handle(): void
    {
        $this->sendEmail($this->email, $this->password);
    }

    private function sendEmail($email, $password): void
    {
        try{
            $mail = new ResetPassword($email, $password);
            Mail::to($email)->send($mail);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }
}
