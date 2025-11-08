<?php

namespace App\Services;

use Vimeo\Vimeo;
use Exception;

class VimeoService
{
    protected $vimeo;

    public function __construct()
    {
        $this->vimeo = new Vimeo(
            env('VIMEO_CLIENT_ID'),
            env('VIMEO_CLIENT_SECRET'),
            env('VIMEO_ACCESS_TOKEN')
        );
    }

    /**
     * Upload a video to Vimeo and return details.
     *
     * @param string $filePath
     * @param string|null $title
     * @param string|null $description
     * @return array
     */
    public function upload(string $filePath, ?string $title = null, ?string $description = null): array
    {
        // try {
        // Upload to Vimeo
        $uri = $this->vimeo->upload($filePath, [
            'name' => $title ?? basename($filePath),
            'description' => $description ?? '',
            'privacy' => [
                'view' => 'unlisted'
            ]
        ]);

        // Get video details
        $videoData = $this->vimeo->request($uri . '?fields=link,pictures');

        return [
            'success' => true,
            'uri' => $uri,
            'link' => $videoData['body']['link'] ?? null,
            'thumbnail' => $videoData['body']['pictures']['sizes'][2]['link'] ?? null,
        ];
        // } catch (Exception $e) {
        //     return [
        //         'success' => false,
        //         'error' => $e->getMessage(),
        //     ];
        // }
    }
}
