<x-guest-layout>
    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-4">
                <div>
                    <h1 class="fw-bold mb-1">Browse Content</h1>
                    <p class="text-muted mb-0">Latest articles, guides, and updates from Ed‑Cademy.</p>
                </div>
                <div class="text-muted small">
                    Showing {{ $blogs->firstItem() ?? 0 }}–{{ $blogs->lastItem() ?? 0 }} of {{ $blogs->total() }}
                </div>
            </div>

            <div class="row g-4">
                @forelse ($blogs as $blog)
                    <div class="col-md-6 col-lg-4">
                        <a class="text-decoration-none" href="{{ route('blogs.show', ['blog' => $blog->slug]) }}">
                            <div class="card h-100 blog-card">
                                @if ($blog->image_url)
                                    <div class="blog-cover" style="background-image:url('{{ $blog->image_url }}')"></div>
                                @else
                                    <div class="blog-cover blog-cover--placeholder">
                                        <div class="text-white-50 fw-semibold">Ed‑Cademy</div>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="small text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $blog->created_at?->format('M d, Y') }}
                                        </div>
                                        <div class="small text-muted">
                                            <i class="bi bi-person me-1"></i>{{ $blog->author }}
                                        </div>
                                    </div>
                                    <h5 class="fw-bold mb-2">{{ $blog->title }}</h5>
                                    <p class="text-muted mb-3">{{ $blog->excerpt }}</p>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($blog->tags_array as $tag)
                                            <span class="badge bg-light text-muted border">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-0 pt-0">
                                    <div class="btn btn-sm btn-outline-primary">
                                        Read article <i class="bi bi-arrow-right ms-1"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="p-5 text-center border rounded-4 bg-white">
                            <h5 class="fw-semibold mb-1">No posts yet</h5>
                            <div class="text-muted">Check back soon.</div>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($blogs->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $blogs->links() }}
                </div>
            @endif
        </div>
    </section>

    @push('styles')
        <style>
            .blog-card {
                border-radius: 18px;
                overflow: hidden;
                transition: transform .15s ease, box-shadow .15s ease;
                box-shadow: 0 10px 28px rgba(0, 0, 0, 0.06);
            }

            .blog-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 14px 34px rgba(0, 0, 0, 0.10);
            }

            .blog-cover {
                height: 180px;
                background-size: cover;
                background-position: center;
            }

            .blog-cover--placeholder {
                background: linear-gradient(135deg, var(--primary-cyan), var(--dark-cyan));
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>
    @endpush
</x-guest-layout>

