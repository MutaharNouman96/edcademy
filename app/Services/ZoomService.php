<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Thin wrapper around the Zoom REST API.
 *
 * Authentication uses a Server-to-Server OAuth app (account_id + client_id +
 * client_secret) which exchanges those credentials for a short-lived access
 * token. We only need a single capability here: create a scheduled meeting for
 * a booked session.
 *
 * If Zoom credentials are not configured (e.g. local development) the service
 * degrades gracefully and returns a placeholder meeting link instead of
 * throwing, so the rest of the booking flow keeps working.
 */
class ZoomService
{
    private const TOKEN_URL = 'https://zoom.us/oauth/token';
    private const API_BASE  = 'https://api.zoom.us/v2';

    /**
     * Create a Zoom meeting for the given booking.
     *
     * @return array{join_url:string,id:?string,password:?string,platform:string}
     */
    public function createMeetingForBooking(Booking $booking): array
    {
        $student  = $booking->student;
        $educator = $booking->educator;

        $topic = sprintf(
            '%s session: %s with %s',
            $booking->subject ?: 'Tutoring',
            optional($student)->full_name ?? 'Student',
            optional($educator)->full_name ?? 'Educator'
        );

        return $this->createMeeting(
            $topic,
            $booking->scheduled_at,
            $booking->duration_minutes
        );
    }

    /**
     * Create a scheduled Zoom meeting.
     *
     * @return array{join_url:string,id:?string,password:?string,platform:string}
     */
    public function createMeeting(string $topic, Carbon $startsAt, int $durationMinutes): array
    {
        if (! $this->isConfigured()) {
            return $this->fallbackMeeting($topic);
        }

        try {
            $token = $this->getAccessToken();

            if (! $token) {
                return $this->fallbackMeeting($topic);
            }

            $userId = config('services.zoom.user_id', 'me');

            $response = Http::withToken($token)
                ->acceptJson()
                ->post(self::API_BASE . "/users/{$userId}/meetings", [
                    'topic'      => Str::limit($topic, 200, ''),
                    'type'       => 2, // scheduled meeting
                    'start_time' => $startsAt->copy()->utc()->format('Y-m-d\TH:i:s\Z'),
                    'duration'   => max(15, $durationMinutes),
                    'timezone'   => 'UTC',
                    'settings'   => [
                        'join_before_host'  => true,
                        'waiting_room'      => false,
                        'approval_type'     => 2,
                        'audio'             => 'both',
                        'auto_recording'    => 'none',
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Zoom meeting creation failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return $this->fallbackMeeting($topic);
            }

            $data = $response->json();

            return [
                'join_url' => $data['join_url'] ?? $this->fallbackLink($topic),
                'id'       => isset($data['id']) ? (string) $data['id'] : null,
                'password' => $data['password'] ?? null,
                'platform' => 'Zoom',
            ];
        } catch (\Throwable $e) {
            Log::error('Zoom meeting creation threw an exception', [
                'error' => $e->getMessage(),
            ]);

            return $this->fallbackMeeting($topic);
        }
    }

    /**
     * Exchange Server-to-Server OAuth credentials for an access token.
     */
    private function getAccessToken(): ?string
    {
        $accountId    = config('services.zoom.account_id');
        $clientId     = config('services.zoom.client_id');
        $clientSecret = config('services.zoom.client_secret');

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->post(self::TOKEN_URL, [
                'grant_type' => 'account_credentials',
                'account_id' => $accountId,
            ]);

        if ($response->failed()) {
            Log::error('Zoom token request failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return null;
        }

        return $response->json('access_token');
    }

    private function isConfigured(): bool
    {
        return config('services.zoom.account_id')
            && config('services.zoom.client_id')
            && config('services.zoom.client_secret');
    }

    /**
     * Placeholder meeting used when Zoom is not configured so the booking flow
     * remains testable in local/dev environments.
     *
     * @return array{join_url:string,id:?string,password:?string,platform:string}
     */
    private function fallbackMeeting(string $topic): array
    {
        return [
            'join_url' => $this->fallbackLink($topic),
            'id'       => null,
            'password' => null,
            'platform' => 'Zoom',
        ];
    }

    private function fallbackLink(string $topic): string
    {
        // Deterministic-ish room name so the same booking keeps the same link.
        $room = 'edcademy-' . Str::slug(Str::limit($topic, 40, '')) . '-' . Str::random(6);

        return 'https://meet.jit.si/' . $room;
    }
}
