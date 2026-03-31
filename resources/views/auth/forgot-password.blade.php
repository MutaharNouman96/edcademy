<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="card-title text-center mb-4">{{ __('Forgot Password?') }}</h3>
                        <div class="mb-3 text-muted small">
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </div>
                        
                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success mb-4" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" autocomplete="off">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-4">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input 
                                    id="email"
                                    type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                >
                                @error('email')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-envelope-arrow-up me-1"></i>
                                    {{ __('Email Password Reset Link') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-4 small text-muted">
                    <a href="{{ route('login') }}">
                        <i class="bi bi-arrow-left"></i> {{ __('Back to Login') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
