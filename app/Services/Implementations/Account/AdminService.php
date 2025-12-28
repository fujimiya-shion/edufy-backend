<?php

namespace App\Services\Implementations\Account;

use App\Jobs\SendResetPasswordEmail;
use App\Models\Admin;
use App\Repositories\Contracts\Account\IAdminRepository;
use App\Services\Implementations\Service;
use App\Services\Contracts\Account\IAdminService;
use Illuminate\Support\Str;

class AdminService extends Service implements IAdminService
{
    public function __construct(IAdminRepository $repository)
    {
        parent::__construct($repository);
    }

    public function resetPassword(Admin $admin): void {
        $newPassword = Str::password(8);
        $admin->password = bcrypt($newPassword);
        $admin->save();

        // Send email to admin with new password
        if (config('app.env') == 'production')
            $mailTo = $admin->email;
        else
            $mailTo = env('MAIL_RESET_PASSWD_TO');

        SendResetPasswordEmail::dispatch($mailTo, $newPassword);
    }
}
