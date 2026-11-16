{{ $appName }}

{{ __('Hello') }}{{ $userName !== '' ? ' '.$userName : '' }},

{{ __('We received a request to reset the password for your :app account.', ['app' => $appName]) }}

{{ __('Open this link to choose a new password:') }}
{{ $url }}

{{ __('This link expires in :minutes minutes and can only be used once.', ['minutes' => $minutes]) }}

{{ __('If you did not request a password reset, no action is required.') }}

{{ $appName }} — {{ __('automated message, please do not reply.') }}
