<?php
/**
 * Trait for processing common
 */
namespace App\Traits;

use App\Models\Smstemplate;
use App\Services\Communication\HostPinnacleSmsService;
use Throwable;
use Illuminate\Support\Facades\Log;

/**
 * Trait for SMS notification and messaging operations
 *
 * Provides functionality for:
 * - Sending SMS notifications for various events
 * - Sending group notification messages
 * - Sending password reset codes via SMS
 * - Sending private messages via SMS
 * - Template-based message composition
 *
 * @package App\Traits
 */
trait SmsProcess
{
	private function deliverConfiguredSms(string $message, string $to): bool
	{
		if (!config('services.hostpinnacle.enabled')) {
			return false;
		}

		try {
			app(HostPinnacleSmsService::class)->send($to, $message);
			return true;
		} catch (Throwable $e) {
			Log::warning('Church SMS delivery failed.', ['error' => $e->getMessage()]);
			return false;
		}
	}

    public function sendSmsNotification($to,$start_date,$location)
    {
        try {
            $template = Smstemplate::where([['name', 'Event'], ['status', '1']])->firstOrFail();
            $content = $template->content;

       	    $content = str_replace(":date",$start_date,$content);
            $content = str_replace(":location",$location,$content);

			return $this->deliverConfiguredSms($content, $to);
        }
        catch(Throwable $e)
        {
            Log::warning('Unable to prepare event SMS.', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendUserNotifyGroup($to,$message)
    {
        try
        {
            $template = Smstemplate::where([['name','permission_credentials'],['status','1']])->firstOrFail();
            $content  = $template->content;

            $content = str_replace(":content",$message,$content);
            return $this->deliverConfiguredSms($content, $to);
        }
        catch(Throwable $e)
        {
            Log::warning('Unable to prepare group notification SMS.', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendUserResetPassword($to,$message)
    {
        try
        {
            $template = Smstemplate::where([['name','reset_password'],['status','1']])->firstOrFail();
            $content  = $template->content;

            $content = str_replace(":token",$message,$content);
            return $this->deliverConfiguredSms($content, $to);
        }
        catch(Throwable $e)
        {
            Log::warning('Unable to prepare password reset SMS.', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendPrivateMessage($to,$message)
    {
        try
        {
            $template = Smstemplate::where([['name','send_sms'],['status',1]])->firstOrFail();
            $content  = $template->content;

            $content = str_replace(":content",$message,$content);
            return $this->deliverConfiguredSms($content, $to);
        }
        catch(Throwable $e)
        {
            Log::warning('Unable to prepare private SMS.', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
