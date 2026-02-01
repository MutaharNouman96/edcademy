<x-guest-layout>
    <section class="py-5">
        <div class="container">
            <a href="{{ route('blogs.index') }}" class="text-decoration-none text-muted d-inline-flex align-items-center mb-3">
                <i class="bi bi-arrow-left me-2"></i> Back to Browse Content
            </a>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="mb-3">
                        <h1 class="fw-bold mb-2">{{ $blog->title }}</h1>
                        <div class="d-flex flex-wrap gap-3 text-muted small">
                            <span><i class="bi bi-person me-1"></i>{{ $blog->author }}</span>
                            <span><i class="bi bi-calendar3 me-1"></i>{{ $blog->created_at?->format('M d, Y') }}</span>
                        </div>
                        @if ($blog->tags_array)
                            <div class="d-flex flex-wrap gap-1 mt-2">
                                @foreach ($blog->tags_array as $tag)
                                    <span class="badge bg-light text-muted border">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if ($blog->image_url)
                        <div class="cover mb-4" style="background-image:url('{{ $blog->image_url }}')"></div>
                    @endif

                    <article class="content-card">
                        {!! $blog->content !!}
                    </article>

                    <div class="mt-4 p-4 rounded-4 bg-white border">
                        <div class="fw-semibold mb-1">More from Ed‑Cademy</div>
                        <div class="text-muted">Explore more articles on the <a href="{{ route('blogs.index') }}">Browse
                                Content</a> page.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .cover {
                height: 360px;
                border-radius: 18px;
                background-size: cover;
                background-position: center;
                box-shadow: 0 14px 34px rgba(0, 0, 0, 0.12);
            }

            .content-card {
                background: #fff;
                border: 1px solid rgba(0, 0, 0, .08);
                border-radius: 18px;
                padding: 2rem;
                box-shadow: 0 10px 28px rgba(0, 0, 0, 0.05);
            }

            .content-card img {
                max-width: 100%;
                height: auto;
                border-radius: 12px;
            }

            .content-card h2,
            .content-card h3,
            .content-card h4 {
                margin-top: 1.4rem;
            }

            .content-card p {
                line-height: 1.8;
            }
        </style>
    @endpush
</x-guest-layout>

