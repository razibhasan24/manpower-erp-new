<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Agent;
use App\Models\Recruitment;
use App\Models\RecruitmentStageHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class SmsService
{
    public function sendAfterCommit(?string $phone, string $message, string $event): void
    {
        $phone = $this->normalizePhone($phone);
        if ($phone === null) {
            return;
        }

        \Illuminate\Support\Facades\DB::afterCommit(function () use ($phone, $message, $event) {
            $this->send($phone, $message, $event);
        });
    }

    public function registration(Recruitment $recruitment): void
    {
        $recruitment->loadMissing(['passportHolder', 'servicePackage']);
        $name = $recruitment->passportHolder?->full_name ?? 'Candidate';
        $service = $this->serviceLabel($recruitment->service_type);
        $message = "Dear {$name}, your {$service} registration {$recruitment->tracking_number} is received. Current status: pending.";
        if ($recruitment->quoted_amount !== null) {
            $message .= ' Package cost: '.$recruitment->quoted_currency.' '.number_format((float) $recruitment->quoted_amount, 2).'.';
        }

        $this->sendAfterCommit($recruitment->passportHolder?->phone, $message, 'registration');
    }

    public function workflow(Recruitment $recruitment, RecruitmentStageHistory $history): void
    {
        $recruitment->loadMissing('passportHolder');
        $service = $this->serviceLabel($recruitment->service_type);
        $stage = str_replace('_', ' ', $history->stage);
        $status = str_replace('_', ' ', $history->status);
        $message = "Your {$service} application {$recruitment->tracking_number} update: {$stage} - {$status}.";

        $this->sendAfterCommit($recruitment->passportHolder?->phone, $message, 'workflow.'.$history->stage.'.'.$history->status);
    }

    public function agentCreated(Agent $agent): void
    {
        $status = $agent->status === 'active' ? 'active' : 'inactive';
        $message = "Dear {$agent->name}, your agent account has been created. Account status: {$status}.";
        $this->sendAfterCommit($agent->phone, $message, 'agent.created');
    }

    private function send(string $phone, string $message, string $event): void
    {
        $driver = (string) config('services.sms.driver', 'log');
        if ($driver === 'log') {
            Log::info('SMS notification (log driver)', ['event' => $event, 'to' => $phone, 'message' => $message]);
            return;
        }

        if ($driver !== 'http') {
            Log::warning('SMS notification skipped: unsupported driver.', ['event' => $event, 'driver' => $driver]);
            return;
        }

        $endpoint = config('services.sms.endpoint');
        if (! is_string($endpoint) || trim($endpoint) === '') {
            Log::warning('SMS notification failed: SMS_HTTP_ENDPOINT is not configured.', ['event' => $event, 'to' => $phone]);
            return;
        }

        $payload = [
            config('services.sms.recipient_key', 'to') => $phone,
            config('services.sms.message_key', 'message') => $message,
        ];
        $sender = config('services.sms.sender');
        if (is_string($sender) && $sender !== '') {
            $payload[config('services.sms.sender_key', 'sender')] = $sender;
        }

        try {
            $request = Http::asJson()->acceptJson()->timeout((int) config('services.sms.timeout', 15));
            $token = config('services.sms.token');
            if (is_string($token) && $token !== '') {
                $scheme = trim((string) config('services.sms.auth_scheme', 'Bearer'));
                $request = $request->withHeaders([
                    (string) config('services.sms.auth_header', 'Authorization') => $scheme === '' ? $token : $scheme.' '.$token,
                ]);
            }

            $response = $request->post($endpoint, $payload)->throw();
            Log::info('SMS notification sent.', ['event' => $event, 'to' => $phone, 'http_status' => $response->status()]);
        } catch (Throwable $exception) {
            // A provider outage must not roll back a saved registration or workflow transition.
            Log::error('SMS notification failed.', [
                'event' => $event,
                'to' => $phone,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function normalizePhone(?string $phone): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        $phone = trim($phone);
        if (str_starts_with($phone, '+')) {
            return '+'.preg_replace('/\D+/', '', substr($phone, 1));
        }

        $digits = preg_replace('/\D+/', '', $phone);
        if (strlen($digits) < 8) {
            return null;
        }
        if (str_starts_with($digits, '00')) {
            return '+'.substr($digits, 2);
        }
        if (str_starts_with($digits, '0') && strlen($digits) === 11) {
            return '+'.config('services.sms.country_code', '880').substr($digits, 1);
        }
        if (str_starts_with($digits, (string) config('services.sms.country_code', '880'))) {
            return '+'.$digits;
        }

        return '+'.$digits;
    }

    private function serviceLabel(?string $type): string
    {
        return match ($type) {
            'student' => 'student',
            'hajj_umrah' => 'Hajj and Umrah',
            default => 'recruitment',
        };
    }
}
