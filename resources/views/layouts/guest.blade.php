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

    @if (env('APP_ENV') == 'local')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/website-style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/glass-landing.css') }}" />



    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light {{ request()->routeIs('website.index') ? 'fixed-top home-nav' : 'scrolled' }}"
        id="navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}"><i class="fas fa-graduation-cap"></i> Ed-Cademy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('web.courses') }}">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Reviews</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="color: var(--primary-cyan)">Browse Content</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('web.eudcator.signup') }}"
                            style="color: var(--accent-pink)">Become an Educator</a>
                    </li>



                    @if (Route::has('login') && Route::has('dashboard'))
                        @if (auth()->check() && auth()->user()->role == 'student')
                            <li class="nav-item ms-lg-2">
                                <a class="nav-link" href="{{ route('student.dashboard') }}">Dashboard</a>
                            </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user-circle me-1"></i> {{ auth()->user()->first_name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Sign out
                                </a>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('student.signup') }}">Register</a>
                            </li>
                        @endif
                    @endauth


                    <li class="nav-item ms-3">
                        <a class="nav-link position-relative" href="{{ route('web.cart') }}">
                            <i class="fas fa-shopping-cart me-2"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger p-2"
                                style="left: 30px;" id="cartCount">
                                {{ cartTotalItems() }}
                            </span>
                        </a>
                    </li>
            </ul>
        </div>
    </div>
</nav>

</nav>
@if (session('success'))
    <x-alert type="success" :message="session('success')" />
@endif

@if (session('error'))
    <x-alert type="error" :message="session('error')" />
@endif

@if (session('info'))
    <x-alert type="info" :message="session('message')" />
@endif

@if (session('warning'))
    <x-alert type="warning" :message="session('warning')" />
@endif

{{ $slot }}

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
                    <a href="{{ route('web.courses') }}">Browse Courses</a>
                    <a href="{{ route('web.educators.index') }}">Find an Educator</a>
                    <a href="#">Free Resources</a>
                    <a href="#">Certificates</a>
                    <a href="#">Student Blog</a>
                </div>
            </div>

            <div class="col-md-2 mb-4">
                <h5 style="color: var(--light-cyan); margin-bottom: 20px">Teach</h5>
                <div class="footer-links">
                    <a href="{{ route('web.eudcator.signup') }}">Become an Educator</a>
                    <a href="#">Create Courses</a>
                    <a href="#">Educator Resources</a>
                    <a href="#">Pricing Plans</a>
                    <a href="#">Success Stories</a>
                </div>
            </div>

            <div class="col-md-2 mb-4">
                <h5 style="color: var(--light-cyan); margin-bottom: 20px">
                    Support
                </h5>
                <div class="footer-links">
                    <a href="#">Help Center</a>
                    <a href="{{ route('web.contact.us') }}">Contact Us</a>
                    <a href="#">Safety & Trust</a>
                    <a href="#">Community Guidelines</a>
                    <a href="#">FAQs</a>
                </div>
            </div>

            <div class="col-md-2 mb-4">
                <h5 style="color: var(--light-cyan); margin-bottom: 20px">
                    Company
                </h5>
                <div class="footer-links">
                    <a href="{{ route('web.about.us') }}">About Us</a>
                    <a href="{{ route('web.educator.policy') }}">Educator Policy</a>
                    <a href="{{ route('web.student.parent.policy') }}">Student Policy</a>
                    <a href="{{ route('web.refund.policy') }}">Refund Policy</a>
                    <a href="{{ route('web.privacy.policy') }}">Privacy Policy</a>
                    <a href="{{ route('web.terms.and.conditions') }}"> Terms & Conditions</a>

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
                    }else{
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
@stack('scripts')
</body>

</html>
