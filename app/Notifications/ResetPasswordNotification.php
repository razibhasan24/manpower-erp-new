<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Services\BrandingService;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Branded password reset email.
 *
 * The URL and expiry always come from Laravel's password broker; this class
 * only controls presentation. No SMTP credentials or secrets live here.
 */
class ResetPasswordNotification extends Notification
{
    public function __construct(public string $token)
    {
    }

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = BrandingService::appName();
        $minutes = (int) config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $data = [
            'appName' => $appName,
            'userName' => $notifiable->name ?? '',
            'url' => $url,
            'minutes' => $minutes,
            'primaryColor' => BrandingService::primaryColor(),
            'logoUrl' => BrandingService::profile()?->logo_path ? route('branding.logo') : null,
        ];

        return (new MailMessage())
            ->subject(__('Reset your :app password', ['app' => $appName]))
            ->view('emails.auth.reset-password', $data);
    }
}
