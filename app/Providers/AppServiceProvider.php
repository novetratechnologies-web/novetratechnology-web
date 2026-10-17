<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Observers\UserprofileObserver;
use App\Observers\MediaFileObserver;
use App\Observers\BulletinObserver;
use App\Observers\GalleryObserver;
use App\Observers\ChurchObserver;
use App\Observers\EventObserver;
use App\Models\MailinglistSubscriber;
use App\Models\Campaign;
use App\Observers\MailinglistSubscriberObserver;
use App\Observers\CampaignObserver;
use App\Models\CampaignEmail;
use App\Observers\CampaignEmailObserver;
use App\Observers\SubscriberObserver;
use App\Models\Subscribers;
use App\Observers\MailinglistObserver;
use App\Models\MailingList;
use App\Models\Email;
use App\Observers\EmailObserver;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\MailManager;
use App\Models\GetResponse;
use App\Observers\GetResponseObserver;
use App\Observers\MailQueueObserver;
use App\Models\MailQueue;
use App\Models\Userprofile;
use App\Models\MediaFile;
use App\Models\Bulletin;
use App\Models\Gallery;
use App\Models\Events;
use App\Models\Church;
use Schema;
use Config;
use App\Models\ChurchDetail;
use Illuminate\Support\Facades\Validator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Userprofile::observe(UserprofileObserver::class);
        MediaFile::observe(MediaFileObserver::class);
        Bulletin::observe(BulletinObserver::class);
        Gallery::observe(GalleryObserver::class);
        Events::observe(EventObserver::class);

        MailinglistSubscriber::observe(MailinglistSubscriberObserver::class);
        Campaign::observe(CampaignObserver::class);

        CampaignEmail::observe(CampaignEmailObserver::class);
        Subscribers::observe(SubscriberObserver::class);
        MailingList::observe(MailinglistObserver::class);
        Email::observe(EmailObserver::class);
        User::observe(UserObserver::class);
        GetResponse::observe(GetResponseObserver::class);
        MailQueue::observe(MailQueueObserver::class);

        Church::observe(ChurchObserver::class);

        Validator::extend('teacher_logoutdevice_id', function ($attribute, $value, $parameters, $validator) 
        {
            $inputs = $validator->getData();
            $email = $inputs['email'];
            $user = User::where('mobile_no', $email)->where('usergroup_id',5)->first();
            
            if ($user!=null) 
            {
                if ($user->device_id == null) 
                {

                    return true;
                }
                return false;
            }
        });

        //dd(Auth::id());
        if (!\App::runningInConsole()
            && Schema::hasTable('settings')
            && Schema::hasTable('church')
            && Schema::hasTable('church_details')) {
            $church = Church::first();

            if ($church) {
                $settings = ChurchDetail::where('church_id', $church->id)->whereIn('meta_key', ['site_title', 'site_description', 'site_keyword', 'favicon', 'church_logo', 'maintenance', 'login_status', 'register_status', 'header_code', 'footer_code', 'facebook_title', 'facebook_description', 'facebook_url', 'facebook_image', 'twitter_title', 'twitter_description', 'twitter_image', 'twitter_url', 'church_full_name', 'church_short_name', 'sitetitle', 'sitename', 'member_web_login', 'guest_login', 'guest_registration', 'guest_register_captcha_status', 'contact_captcha_status'])->get();

                //$settings = Setting::all();

                foreach ($settings as $key => $setting) {
                    $key = $setting->meta_key;

                    if ($setting->meta_key == 'church_logo') {
                        $key = 'logo';
                        //Config::set('settings.login_status', 1);
                    }
                    //Config::set( 'settings.'.$key, $setting->meta_value );

                    Config::set('settings.' . $key, $setting->meta_value);
                }
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('emails.maillist', function ($app, $parameters) {
            $encryption = $parameters['smtp_encryption'] ?? null;
            $port = (int) ($parameters['smtp_port'] ?? 587);
            $scheme = $encryption === 'ssl' || ($encryption === 'tls' && $port === 465) || ($encryption === null && $port === 465)
                ? 'smtps'
                : 'smtp';

            $mailConfig = [
                'transport' => 'smtp',
                'scheme' => $scheme,
                'host' => $parameters['smtp_host'],
                'port' => $port,
                'username' => $parameters['smtp_username'] ?? null,
                'password' => $parameters['smtp_password'] ?? null,
                'encryption' => $encryption,
            ];

            $transport = $app->make(MailManager::class)->createSymfonyTransport($mailConfig);
            $mailer = new Mailer('church_mailing_list', $app['view'], $transport, $app['events']);
            $mailer->setQueue($app['queue']);
            $mailer->alwaysFrom($parameters['from_email'], $parameters['from_name'] ?? null);
            $mailer->alwaysReplyTo($parameters['from_email'], $parameters['from_name'] ?? null);

            return $mailer;
        });
    }
}
