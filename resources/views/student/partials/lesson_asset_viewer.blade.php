@php
    $assetUrl = $url ?? null;
    $downloadLabel = $downloadLabel ?? 'Download file';

    $extension = '';
    if ($assetUrl) {
        $path = parse_url($assetUrl, PHP_URL_PATH) ?? $assetUrl;
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }

    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
    $isPdf = $extension === 'pdf';
    $isImage = in_array($extension, $imageExtensions, true);
    $isBrowserPreviewable = $isPdf || $isImage;
@endphp

@if ($assetUrl && $isBrowserPreviewable)
    <div class="lesson-asset-viewer mb-2">
        @if ($isPdf)
            <object data="{{ $assetUrl }}" type="application/pdf" class="w-100 rounded border bg-light"
                style="height: 350px;">
                <iframe src="{{ $assetUrl }}" class="w-100 rounded" style="height: 350px; border: 0;"
                    title="PDF preview"></iframe>
            </object>
        @else
            <img src="{{ $assetUrl }}" alt="{{ $downloadLabel }}" class="img-fluid rounded border bg-light"
                style="max-height: 350px; width: 100%; object-fit: contain;">
        @endif
    </div>
    <div class="mt-2">
        <a href="{{ $assetUrl }}" target="_blank" rel="noopener noreferrer"
            class="btn btn-sm btn-outline-primary">Open in new tab</a>
    </div>
@elseif ($assetUrl)
    <a href="{{ $assetUrl }}" class="btn btn-sm btn-outline-success mt-3" download>
        {{ $downloadLabel }}
    </a>
@endif
