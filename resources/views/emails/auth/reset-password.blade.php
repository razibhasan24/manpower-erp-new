<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Reset your password') }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f5f9;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f5f9;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">
                    <tr>
                        <td style="padding:22px 28px;background:{{ $primaryColor }};color:#ffffff;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="font-size:18px;font-weight:700;">{{ $appName }}</td>
                                    @if($logoUrl)
                                        <td align="right"><img src="{{ $logoUrl }}" alt="" height="30" style="display:block;max-height:30px;"></td>
                                    @endif
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 28px;">
                            <p style="margin:0 0 14px;font-size:15px;">{{ __('Hello') }}{{ $userName !== '' ? ' '.$userName : '' }},</p>
                            <p style="margin:0 0 18px;font-size:14px;line-height:1.6;color:#4b5563;">
                                {{ __('We received a request to reset the password for your :app account. Click the button below to choose a new password.', ['app' => $appName]) }}
                            </p>
                            <p style="margin:0 0 22px;text-align:center;">
                                <a href="{{ $url }}" style="display:inline-block;padding:12px 22px;border-radius:8px;background:{{ $primaryColor }};color:#ffffff;text-decoration:none;font-size:14px;font-weight:700;">
                                    {{ __('Reset password') }}
                                </a>
                            </p>
                            <p style="margin:0 0 12px;font-size:13px;line-height:1.6;color:#6b7280;">
                                {{ __('This link expires in :minutes minutes and can only be used once.', ['minutes' => $minutes]) }}
                            </p>
                            <p style="margin:0 0 12px;font-size:13px;line-height:1.6;color:#6b7280;">
                                {{ __('If the button does not work, copy and paste this address into your browser:') }}
                            </p>
                            <p style="margin:0 0 20px;font-size:12px;line-height:1.6;color:#2563eb;word-break:break-all;">
                                {{ $url }}
                            </p>
                            <p style="margin:0;font-size:13px;line-height:1.6;color:#6b7280;">
                                {{ __('If you did not request a password reset, no action is required. Your password will not change until the link above is used.') }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;background:#f9fafb;border-top:1px solid #e5e7eb;font-size:11px;color:#9ca3af;line-height:1.5;">
                            {{ $appName }} &middot; {{ __('This is an automated message, please do not reply.') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
