<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="card-title text-center mb-4">{{ __('Confirm Password') }}</h3>
                        
                        <div class="mb-3 text-muted small">
                            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                        </div>

                        <form method="POST" action="{{ route('password.confirm') }}" autocomplete="off">
                            @csrf

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input 
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required
                                    autocomplete="current-password"
                                >
                                @error('password')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-shield-lock me-1"></i>
                                    {{ __('Confirm') }}
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
