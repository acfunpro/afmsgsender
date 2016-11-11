<?php

namespace acfunpro\afmsgsender;

use Illuminate\Support\ServiceProvider;

class MsgsenderServiceProvider extends ServiceProvider
{
    /**
     * bootstrap
     */
    public function boot()
    {
        //publish config files
        $this->publishes([
            __DIR__ . '/config/message.php' => config_path('message.php'),
            __DIR__ . '/config/sendemail.php' => config_path('sendemail.php'),
        ], 'config');

        //publish view files
        $this->publishes([
            __DIR__ . '/views/emails/register.blade.php' => resource_path('/views/emails/register.blade.php'),
            __DIR__ . '/views/emails/bindemail.blade.php' => resource_path('/views/emails/bindemail.blade.php'),
            __DIR__ . '/views/emails/resetpwd.blade.php' => resource_path('/views/emails/resetpwd.blade.php'),
        ], 'views');

        //publish language files
        $this->publishes([
            __DIR__ . '/lang/zh-cn/message.php' => resource_path('/lang/zh-cn/langmessage.php'),
            __DIR__ . '/lang/zh-cn/email.php' => resource_path('/lang/zh-cn/sendemail.php'),
        ], 'lang');
    }


    /**
     * register service provider
     */
    public function register()
    {
        //
    }
}