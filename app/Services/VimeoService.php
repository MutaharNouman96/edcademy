<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Vimeo\Laravel\Facades\Vimeo;

class VimeoService
{
    /**
     * Verify the configured Vimeo access token and return its permissions (scopes).
     * Uses GET https://api.vimeo.com/oauth/verify with the token from config.
     *
     * Test in tinker: (new \App\Services\VimeoService())->verifyAccessToken()
     *
     * @param string|null $accessToken Optional token to verify; defaults to config value (VIMEO_ACCESS).
     * @return array{success: bool, status?: int, body?: array, scopes?: string[], message?: string}
     */
    public function verifyAccessToken(?string $accessToken = null): array
    {
        $token = $accessToken ?? config('vimeo.connections.main.access_token');

        if (empty($token)) {
            return [
                'success' => false,
                'message' => 'No access token configured. Set VIMEO_ACCESS in .env or pass a token.',
            ];
        }

        try {
            if ($accessToken !== null) {
                $http = Http::withToken($token)
                    ->withHeaders(['Accept' => 'application/vnd.vimeo.*+json;version=3.4'])
                    ->get('https://api.vimeo.com/oauth/verify');
                $status = $http->status();
                $body = $http->json() ?? [];
            } else {
                $response = Vimeo::request('/oauth/verify', [], 'GET');
                $body = $response['body'] ?? [];
                $status = $response['status'] ?? 0;
            }

            $scopes = isset($body['scope']) && is_string($body['scope'])
                ? explode(' ', trim($body['scope']))
                : [];

            return [
                'success' => $status === 200,
                'status' => $status,
                'body' => $body,
                'scopes' => $scopes,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Verify request failed: ' . $e->getMessage(),
            ];
        }
    }

    public function uploadVideo(Request $request): array
    {
        // 1. Validation (Highly Recommended)
        $validated = Validator::make($request->all(), [
            'video' => 'required|file|mimes:mp4,mov,avi,wmv|max:512000', // 500MB max (Laravel max is in KB)
            'title'      => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validated->fails()) {
            return [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validated->errors(),
            ];
        }

        // Get the uploaded file path
        $fullPathToVideo = $request->file('video')->getRealPath();

        // 2. Define Video Metadata
        $videoData = [
            'name'        => $request->input('title'),
            'description' => $request->input('description'),
            // Optional: Set privacy (e.g., 'nobody', 'anybody', 'password', 'unlisted')
            'privacy'     => [
                'view' => 'unlisted' // Common setting for videos embedded on a private site
            ]
        ];

        try {
            // 3. Upload the video using the Vimeo Facade
            // The upload method handles the entire process: creation, upload, and finalization.
            // The response contains the video URI (e.g., '/videos/123456789')
            $uri = Vimeo::upload($fullPathToVideo, $videoData);

            // 4. Extract Video ID and build embed URL
            // The URI is typically in the format /videos/123456789
            $videoId = basename($uri);
            $embedUrl = 'https://player.vimeo.com/video/' . $videoId;

            return [
                'success' => true,
                'message' => 'Video uploaded successfully',
                'video_id' => $videoId,
                'video_uri' => $uri,
                'link' => $embedUrl, // Embed URL for player; also used by LessonController
            ];
        } catch (\Exception $e) {
            // Handle Vimeo API errors or other exceptions
            return [
                'success' => false,
                'message' => 'Video upload failed: ' . $e->getMessage()
            ];
        }
    }
}
