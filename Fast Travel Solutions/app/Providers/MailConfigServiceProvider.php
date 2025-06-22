<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $emailServicesEmail = Setting::where('parameter', 'smtp_username')->first();
        $emailServicesPassword = Setting::where('parameter', 'smtp_password')->first();
        $emailServicesFromEmail = Setting::where('parameter', 'smtp_from_email')->first();
        $emailServicesFromName = Setting::where('parameter', 'smtp_from_name')->first();
        $emailServicesHost = Setting::where('parameter', 'smtp_host')->first();
        $emailServicesPort = Setting::where('parameter', 'smtp_port')->first();
        $emailServicesEncryptoon = Setting::where('parameter', 'smtp_encryption')->first();

        if ($emailServicesEmail && $emailServicesPassword) {
            $config = array(

                'driver'     => 'smtp',
                'host'       => $emailServicesHost->value,
                'port'       => $emailServicesPort->value,
                'username'   => $emailServicesEmail->value,
                'password'   => $emailServicesPassword->value,
                'encryption' => $emailServicesEncryptoon->value,
                'from'       => array('address' => $emailServicesFromEmail->value, 'name' => $emailServicesFromName->value),
                'sendmail'   => '/usr/sbin/sendmail -bs',
                'pretend'    => false,'sendmail'   => '/usr/sbin/sendmail -bs',
                'pretend'    => false,
            );

            Config::set('mail', $config);
        }
    }
}
