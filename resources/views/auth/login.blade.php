<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <style>
        :root {
            --primary-cyan: #00838f;
            --light-cyan: #4fb3bf;
            --dark-cyan: #005662;
        }

        body {
            background: linear-gradient(135deg, var(--primary-cyan), var(--light-cyan));
            min-height: 100vh; 

            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        #login-container {
           
           
            align-items: center;
            justify-content: center;
            padding: 10rem 1rem;
        }

        .login-card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
        }

        .login-title {
            color: var(--dark-cyan);
            font-weight: 700;
        }

        .form-control:focus {
            border-color: var(--primary-cyan);
            box-shadow: 0 0 0 0.2rem rgba(0, 131, 143, 0.25);
        }

        .btn-cyan {
            background-color: var(--primary-cyan);
            border: none;
            color: #fff;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cyan:hover {
            background-color: var(--dark-cyan);
        }

        .text-cyan {
            color: var(--primary-cyan);
        }

        .text-cyan:hover {
            color: var(--dark-cyan);
        }

        .form-check-input:checked {
            background-color: var(--primary-cyan);
            border-color: var(--primary-cyan);
        }
    </style>

    <div class="container" id="login-container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="login-card">

                    <div class="text-center mb-4">
                        <h3 class="login-title">Welcome Back 👋</h3>
                        <p class="text-muted mb-0">Login to your Ed-Cademy account</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <input type="hidden" hidden name="redirect_url" value="{{ request('redirect_url') }}">

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autofocus autocomplete="username">

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password">

                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me + Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                <label class="form-check-label" for="remember_me">
                                    Remember me
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="text-cyan text-decoration-none small" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-cyan py-2">
                                Log In
                            </button>
                        </div>

                        <!-- Register Link -->
                       
                    </form>

                </div>
            </div>
        </div>
    </div>


</x-guest-layout>
