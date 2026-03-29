<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ed-Cademy - Learn, Connect, Succeed!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Teachers:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- @if (env('APP_ENV') == 'local')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif --}}
    <link rel="stylesheet" href="{{ asset('assets/css/website-style.css?v=' . time()) }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/glass-landing.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/select2-addon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}" />
    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg shadow-sm custom-navbar fixed-top" id="navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 py-2 px-3 rounded-3" href="{{ url('/') }}">
                <span class="brand-icon-container"><i class="fas fa-graduation-cap"></i></span>
                <span class="fw-bold brand-text">Ed-Cademy</span>
            </a>
            <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-grow-0" id="navbarNav">
                <ul class="navbar-nav align-items-lg-center ms-auto gap-lg-2 gap-1 fs-6 fw-semibold custom-nav-list">
                    <li class="nav-item">
                        <a class="nav-link" href="#features"><i class="bi bi-stars d-lg-none me-2"></i>Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('web.courses') }}"><i
                                class="bi bi-journal d-lg-none me-2"></i>Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('web.how.it.works') }}"><i
                                class="bi bi-lightbulb d-lg-none me-2"></i>How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('web.reviews') }}"><i
                                class="bi bi-chat-text d-lg-none me-2"></i>Reviews</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blogs.index') }}"><i
                                class="bi bi-file-earmark-text d-lg-none me-2"></i>Browse Content</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('web.eudcator.signup') }}">
                            <i class="bi bi-person-plus d-lg-none me-2"></i>Become an Educator
                        </a>
                    </li>

                    @if (Route::has('login') && Route::has('dashboard'))
                        @if (auth()->check() && auth()->user()->role == 'student')
                            <li class="nav-item ms-lg-4">
                                <a class="nav-link" href="{{ route('student.dashboard') }}">
                                    <i class="bi bi-grid-1x2 d-lg-none me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-user-circle me-1"></i> {{ auth()->user()->first_name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end mt-2 rounded-3 custom-dropdown">
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i>Sign out
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" id="logout-form"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @elseif (auth()->check() && auth()->user()->role == 'educator')
                            <li class="nav-item  ms-lg-4">
                                <a class="nav-link" href="{{ route('educator.dashboard') }}">
                                    <i class="bi bi-grid-1x2 d-lg-none me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-user-circle me-1"></i> {{ auth()->user()->first_name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end mt-2 rounded-3 custom-dropdown">
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i>Sign out
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" id="logout-form"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @elseif (auth()->check() && auth()->user()->role == 'admin')
                            <li class="nav-item  ms-lg-4">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-grid-1x2 d-lg-none me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-user-circle me-1"></i> {{ auth()->user()->first_name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end mt-2 rounded-3 custom-dropdown">
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i>Sign out
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" id="logout-form"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item  ms-lg-4">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="bi bi-person-circle d-lg-none me-2"></i>Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('student.signup') }}">
                                    <i class="bi bi-person-plus d-lg-none me-2"></i>Register
                                </a>
                            </li>
                        @endif
                    @endif

                    <li class="nav-item ">
                        <a class="nav-link cart-link d-flex align-items-center position-relative px-2"
                            href="{{ route('web.cart') }}" title="Cart">
                            <i class="fas fa-shopping-cart me-2"></i>
                            <span
                                class="cart-count-badge badge rounded-pill align-items-center justify-content-center d-flex"
                                id="cartCount">
                                {{ cartTotalItems() }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show my-3" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show my-3" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div @if (!request()->routeIs('website.index')) class="mt-extra-space" @endif>
        {{ $slot }}
    </div>
    <!-- Footer -->
    <footer class="">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4 style="color: var(--light-cyan); margin-bottom: 20px">
                        <i class="fas fa-graduation-cap"></i> Ed-Cademy
                    </h4>
                    <p style="color: #ccc">
                        Making learning fun, accessible, and rewarding for students
                        everywhere. Join the future of education today!
                    </p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <div class="col-md-2 mb-4">
                    <h5 style="color: var(--light-cyan); margin-bottom: 20px">Learn</h5>
                    <div class="footer-links">
                        <a href="{{ route('content.browse-courses') }}">Browse Courses</a>
                        <a href="{{ route('content.find-an-educator') }}">Find an Educator</a>
                        <a href="{{ route('content.free-resources') }}">Free Resources</a>
                        <a href="{{ route('content.certificates') }}">Certificates</a>
                        <a href="{{ route('content.student-blog') }}">Student Blog</a>
                    </div>
                </div>

                <div class="col-md-2 mb-4">
                    <h5 style="color: var(--light-cyan); margin-bottom: 20px">Teach</h5>
                    <div class="footer-links">
                        <a href="{{ route('web.eudcator.signup') }}">Become an Educator</a>
                        <a href="{{ route('content.create-courses') }}">Create Courses</a>
                        <a href="{{ route('content.educator-resources') }}">Educator Resources</a>
                        <a href="{{ route('content.pricing-plans') }}">Pricing Plans</a>
                        <a href="{{ route('content.success-stories') }}">Success Stories</a>
                    </div>
                </div>

                <div class="col-md-2 mb-4">
                    <h5 style="color: var(--light-cyan); margin-bottom: 20px">
                        Support
                    </h5>
                    <div class="footer-links">
                        <a href="{{ route('content.help-center') }}">Help Center</a>
                        <a href="{{ route('web.contact.us') }}">Contact Us</a>
                        <a href="{{ route('content.safety-and-trust') }}">Safety & Trust</a>
                        <a href="{{ route('content.community-guidelines') }}">Community Guidelines</a>
                        <a href="{{ route('content.faqs') }}">FAQs</a>
                    </div>
                </div>

                <div class="col-md-2 mb-4">
                    <h5 style="color: var(--light-cyan); margin-bottom: 20px">
                        Company
                    </h5>
                    <div class="footer-links">
                        <a href="{{ route('web.about-us') }}">About Us</a>
                        @foreach (App\Models\Policy::all() as $policy)
                            <a href="{{ route('web.policy', $policy->slug) }}">{{ $policy->name }}</a>
                        @endforeach

                    </div>
                </div>
            </div>

            <hr style="border-color: #444; margin: 30px 0" />

            <div class="text-center" style="color: #888">
                <p>
                    &copy; 2025 Ed-Cademy. All rights reserved. Made with ❤️ for
                    students everywhere.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault()
                const target = document.querySelector(this.getAttribute('href'))
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    })
                }
            })
        })



        // Animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        }

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1'
                    entry.target.style.transform = 'translateY(0)'
                }
            })
        }, observerOptions)

        document
            .querySelectorAll(
                '.feature-card, .course-card, .testimonial-card, .step-card'
            )
            .forEach(el => {
                el.style.opacity = '0'
                el.style.transform = 'translateY(30px)'
                el.style.transition = 'all 0.6s ease'
                observer.observe(el)
            })

        @if (request()->routeIs('website.index'))
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('#navbar')
                if (window.scrollY > 100) {
                    navbar.classList.add('scrolled')
                } else {
                    navbar.classList.remove('scrolled')
                }
            })
        @endif
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.add-to-cart-form').on('submit', function(e) {
                e.preventDefault(); // Stop page reload

                const form = $(this);
                const btn = form.find('.cart-btn');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.status === true) {
                            btn.text('Added to cart');
                            btn.prop('disabled', true);

                            Swal.fire({
                                icon: 'success',
                                title: 'Added to cart!',
                                text: 'Item added successfully',
                                timer: 1000,
                                showConfirmButton: true
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to add to cart : ' + res.message,
                                timer: 1000,
                                showConfirmButton: true
                            })
                        }

                        console.log('Added:', res);
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add to cart : ' + err.responseJSON.message,
                            timer: 1000,
                            showConfirmButton: true
                        })
                        console.error('Failed:', err);
                    }
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select an option',
                allowClear: true,

            });
        });
    </script>
    @stack('scripts')
</body>

</html>
