<x-guest-layout>
    <style>
        .auth-register {
            /* Educator (cyan) palette - educator-style.css */
            --edu-primary: #006b7d;
            --edu-dark: #004a57;
            --edu-light: #e0f7fa;
            --edu-accent: #ff6b35;

            /* Student (purple) palette - app.css */
            --stu-primary: #6f42c1;
            --stu-dark: #4b2a87;
            --stu-light: #f3e8ff;
            --stu-accent: #00b3a4;

            position: relative;
            min-height: 100vh;
            padding: 130px 0 80px;
            background:
                radial-gradient(900px 500px at 0% 0%, rgba(111, 66, 193, 0.10), transparent 60%),
                radial-gradient(900px 500px at 100% 100%, rgba(0, 107, 125, 0.10), transparent 60%),
                linear-gradient(135deg, #fafbff 0%, #f6f8f9 100%);
            overflow: hidden;
        }

        .auth-register__header {
            text-align: center;
            margin-bottom: 48px;
            position: relative;
            z-index: 1;
        }

        .auth-register__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 0, 0, 0.06);
            padding: 6px 14px;
            border-radius: 999px;
            color: #475569;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 14px;
        }

        .auth-register__title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .auth-register__subtitle {
            color: #64748b;
            font-size: 1.05rem;
            max-width: 520px;
            margin: 0 auto;
        }

        .auth-register__cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            max-width: 980px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .auth-register__card {
            position: relative;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            border-radius: 24px;
            padding: 36px 32px 32px;
            min-height: 420px;
            color: #fff;
            overflow: hidden;
            transition: transform 0.35s ease, box-shadow 0.35s ease;
            isolation: isolate;
        }

        .auth-register__card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 24px;
            z-index: -2;
        }

        .auth-register__card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 24px;
            background:
                radial-gradient(400px 200px at 100% 0%, rgba(255, 255, 255, 0.18), transparent 60%),
                radial-gradient(300px 200px at 0% 100%, rgba(255, 255, 255, 0.10), transparent 60%);
            z-index: -1;
        }

        .auth-register__card--educator::before {
            background: linear-gradient(135deg, var(--edu-primary) 0%, var(--edu-dark) 100%);
        }

        .auth-register__card--educator {
            box-shadow: 0 20px 40px rgba(0, 107, 125, 0.25);
        }

        .auth-register__card--student::before {
            background: linear-gradient(135deg, var(--stu-primary) 0%, var(--stu-dark) 100%);
        }

        .auth-register__card--student {
            box-shadow: 0 20px 40px rgba(111, 66, 193, 0.25);
        }

        .auth-register__card:hover {
            transform: translateY(-8px);
            color: #fff;
        }

        .auth-register__card--educator:hover {
            box-shadow: 0 26px 50px rgba(0, 107, 125, 0.40);
        }

        .auth-register__card--student:hover {
            box-shadow: 0 26px 50px rgba(111, 66, 193, 0.40);
        }

        .auth-register__icon {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 22px;
            backdrop-filter: blur(6px);
            transition: transform 0.35s ease;
        }

        .auth-register__card:hover .auth-register__icon {
            transform: scale(1.06) rotate(-3deg);
        }

        .auth-register__badge {
            position: absolute;
            top: 22px;
            right: 22px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.28);
            color: #fff;
            backdrop-filter: blur(6px);
        }

        .auth-register__card--educator .auth-register__badge {
            background: var(--edu-accent);
            border-color: transparent;
        }

        .auth-register__card--student .auth-register__badge {
            background: var(--stu-accent);
            border-color: transparent;
        }

        .auth-register__role {
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            margin-bottom: 8px;
        }

        .auth-register__desc {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.975rem;
            margin-bottom: 22px;
            line-height: 1.55;
        }

        .auth-register__features {
            list-style: none;
            padding: 0;
            margin: 0 0 28px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .auth-register__features li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.92rem;
            color: rgba(255, 255, 255, 0.92);
        }

        .auth-register__features li i {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            flex-shrink: 0;
        }

        .auth-register__cta {
            margin-top: auto;
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 14px 22px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .auth-register__card--educator .auth-register__cta {
            color: var(--edu-dark);
        }

        .auth-register__card--student .auth-register__cta {
            color: var(--stu-dark);
        }

        .auth-register__card:hover .auth-register__cta {
            background: #fff;
        }

        .auth-register__cta i {
            transition: transform 0.25s ease;
        }

        .auth-register__card:hover .auth-register__cta i {
            transform: translateX(4px);
        }

        .auth-register__footer {
            text-align: center;
            margin-top: 36px;
            color: #64748b;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        .auth-register__footer a {
            color: #0f172a;
            font-weight: 700;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: border-color 0.2s ease;
        }

        .auth-register__footer a:hover {
            border-bottom-color: #0f172a;
        }

        @media (max-width: 768px) {
            .auth-register {
                padding: 110px 0 60px;
            }
            .auth-register__title {
                font-size: 1.85rem;
            }
            .auth-register__subtitle {
                font-size: 0.95rem;
            }
            .auth-register__cards {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 0 16px;
            }
            .auth-register__card {
                min-height: auto;
                padding: 28px 24px;
            }
            .auth-register__role {
                font-size: 1.45rem;
            }
        }
    </style>

    <section class="auth-register">
        <div class="container">
            <div class="auth-register__header">
                <span class="auth-register__eyebrow">
                    <i class="bi bi-stars"></i> Get started in minutes
                </span>
                <h1 class="auth-register__title">Join Ed-Cademy</h1>
                <p class="auth-register__subtitle">
                    Choose the path that suits you best — start teaching or start learning today.
                </p>
            </div>

            <div class="auth-register__cards">
                <!-- Educator Card -->
                <a href="{{ route('web.eudcator.signup') }}" class="auth-register__card auth-register__card--educator">
                    <span class="auth-register__badge">For Educators</span>
                    <div class="auth-register__icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="auth-register__role">I’m an Educator</div>
                    <p class="auth-register__desc">
                        Share your expertise, build courses, and grow a community of learners.
                    </p>
                    <ul class="auth-register__features">
                        <li><i class="bi bi-check-lg"></i> Create &amp; sell unlimited courses</li>
                        <li><i class="bi bi-check-lg"></i> Engage students with live sessions</li>
                        <li><i class="bi bi-check-lg"></i> Track earnings &amp; performance</li>
                    </ul>
                    <span class="auth-register__cta">
                        Become an Educator <i class="bi bi-arrow-right"></i>
                    </span>
                </a>

                <!-- Student Card -->
                <a href="{{ route('student.signup') }}" class="auth-register__card auth-register__card--student">
                    <span class="auth-register__badge">For Students</span>
                    <div class="auth-register__icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="auth-register__role">I’m a Student</div>
                    <p class="auth-register__desc">
                        Discover top courses, learn at your own pace, and unlock new opportunities.
                    </p>
                    <ul class="auth-register__features">
                        <li><i class="bi bi-check-lg"></i> Access expert-led courses</li>
                        <li><i class="bi bi-check-lg"></i> Learn from anywhere, anytime</li>
                        <li><i class="bi bi-check-lg"></i> Earn certificates &amp; track progress</li>
                    </ul>
                    <span class="auth-register__cta">
                        Sign up as a Student <i class="bi bi-arrow-right"></i>
                    </span>
                </a>
            </div>

            <div class="auth-register__footer">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </section>
</x-guest-layout>
