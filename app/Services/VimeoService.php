<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vimeo\Laravel\Facades\Vimeo; // Import the Facade

class VimeoService
{
   
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
