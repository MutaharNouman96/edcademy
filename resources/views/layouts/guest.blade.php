<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ed-Cademy - Learn, Connect, Succeed!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Teachers:wght@400;700&display=swap" rel="stylesheet">

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
                </ul>
            </div>
        </div>
    </nav>

    </nav>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('message'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{ $slot }}

    <!-- Footer -->
    <footer class="mt-5">
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
                        <a href="#">Browse Courses</a>
                        <a href="#">Find an Educator</a>
                        <a href="#">Free Resources</a>
                        <a href="#">Certificates</a>
                        <a href="#">Student Blog</a>
                    </div>
                </div>

                <div class="col-md-2 mb-4">
                    <h5 style="color: var(--light-cyan); margin-bottom: 20px">Teach</h5>
                    <div class="footer-links">
                        <a href="#">Become an Educator</a>
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
                        <a href="#">Contact Us</a>
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
                        <a href="#">About Us</a>
                        <a href="#">Careers</a>
                        <a href="#">Press</a>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
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
    @stack('scripts')
</body>

</html>
