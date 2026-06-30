@php
    $icon = $doc['icon'] ?? match ($doc['kind'] ?? 'other') {
        'pdf' => 'bi-file-earmark-pdf',
        'image' => 'bi-file-earmark-image',
        'video' => 'bi-camera-video',
        default => 'bi-file-earmark',
    };
    $title = $doc['label'] ?? $doc['name'] ?? 'Document';
@endphp
<div class="doc-preview-card h-100">
    <div class="doc-preview-card__icon">
        <i class="bi {{ $icon }}"></i>
    </div>
    <div class="doc-preview-card__body">
        <div class="doc-preview-card__label">{{ $title }}</div>
        <div class="doc-preview-card__name" title="{{ $doc['name'] }}">{{ $doc['name'] }}</div>
        <div class="doc-preview-card__actions">
            <button type="button" class="btn btn-sm btn-primary btn-doc-preview"
                data-path="{{ $doc['path'] }}" data-title="{{ $title }}"
                data-kind="{{ $doc['kind'] ?? 'other' }}">
                <i class="bi bi-eye me-1"></i> Preview
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary btn-doc-open"
                data-path="{{ $doc['path'] }}">
                <i class="bi bi-box-arrow-up-right"></i>
            </button>
            @if (!empty($removable))
                @if (($type ?? '') === 'additional_document' && !empty($doc['id']))
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-additional-doc"
                        data-url="{{ route('educator.verification.document.destroy', $doc['id']) }}">
                        <i class="bi bi-trash"></i>
                    </button>
                @elseif (!empty($type) && $type !== 'additional_document')
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-profile-doc"
                        data-type="{{ $type }}"
                        data-url="{{ route('educator.verification.profile-document.destroy', $type) }}">
                        <i class="bi bi-trash"></i>
                    </button>
                @endif
            @endif
        </div>
    </div>
</div>
