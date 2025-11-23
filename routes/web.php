<?php

use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\Educator\ReviewController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Educator\DashboardController as EducatorDashboardController;
use App\Http\Controllers\Educator\EarningController;
use App\Http\Controllers\Educator\LessonVideoViewsController;
use App\Http\Controllers\Educator\PayoutController;
use App\Http\Controllers\Educator\SessionController;
use App\Http\Controllers\Educator\VideoStatController;
use App\Http\Controllers\EducatorController;

use App\Http\Controllers\Educator\Settings\{
    ProfileSettingController,
    SecuritySettingController,
    PaymentSettingController,
    PaymentMethodController,
    AvailabilitySettingController,
    PrivacySettingController,
    VerificationSettingController,
    ConnectionController,
    PreferenceController
};
use App\Http\Controllers\NotificationSettingController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\StudentDashboardController;
use Illuminate\Support\Facades\Broadcast;

//livewire routes


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [WebsiteController::class, 'index']);
Route::get("become-an-educator", [WebsiteController::class, "educator_signup"])->name("web.eudcator.signup");
Route::post("educator/signup/store", [EducatorController::class, "store"])->name("educator.signup.store");
Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        Route::get("payouts", [App\Http\Controllers\Admin\PayoutController::class, 'index'])->name('admin.payouts.index');
        Route::get("payout/{payout}", [App\Http\Controllers\Admin\PayoutController::class, 'show'])->name("admin.payouts.show");
        Route::post("process/payout/{payout}", [App\Http\Controllers\Admin\PayoutController::class, 'process'])->name("admin.payouts.process");

        Route::resource('earnings', App\Http\controllers\Admin\EarningController::class)->only(['index', 'show']);
    });

// Educator routes

Route::middleware(['auth', 'role:educator', 'verified'])
    ->prefix('educator')
    ->group(function () {
        Route::get('dashboard', [EducatorDashboardController::class, 'index'])
            ->name('educator.dashboard');
        Route::get('/lesson-views/chart', [LessonVideoViewsController::class, 'viewsChart']);

        Route::get('profile', [EducatorDashboardController::class, 'profile'])
            ->name('educator.profile');
        Route::put('profile', [EducatorDashboardController::class, 'profile_update'])->name('educator.profile.update');

        Route::get("video-stats", [VideoStatController::class, 'index'])
            ->name('educator.video-stats.index');

        Route::get("video-stats/{video}", [VideoStatController::class, 'show']);

        Route::get("sessions", [SessionController::class, 'index'])->name('educator.sessions.index');
        Route::get("sessions/create", [SessionController::class, 'create'])->name('educator.sessions.create');
        Route::post("sessions/store", [SessionController::class, 'store'])->name('educator.sessions.store');

        Route::get('payments', [\App\Http\Controllers\Educator\PaymentController::class, 'index'])->name('educator.payments.index');
        Route::get("payment/{payment}", [\App\Http\Controllers\Educator\PaymentController::class, 'show'])->name("educator.payments.show");

        Route::resource('earnings', EarningController::class)->only(['index', 'show'])->names('educator.earnings');


        Route::get("payouts", [PayoutController::class, 'index'])->name('educator.payouts.index');

        Route::resource('courses', \App\Http\Controllers\Educator\CoursesController::class)->names('educator.courses');

        Route::get("reviews", [ReviewController::class, "index"])->name("educator.reviews.index");


        Route::get("course/get/sections/{course_id}", [\App\Http\Controllers\Educator\CoursesController::class, "course_sections"])->middleware('api.auth');
        Route::post("courses/section/{course_id}", [\App\Http\Controllers\Educator\CoursesController::class, "post_course_section"])->middleware('api.auth');
        Route::delete("courses/section/{section_id}", [\App\Http\Controllers\Educator\CoursesController::class, "delete_course_section"])
            ->name('educator.courses.section.delete')
            ->middleware('api.auth');

        Route::prefix('lessons')->group(function () {
            Route::post('/store', [\App\Http\Controllers\Educator\LessonController::class, 'store']);
            Route::put('update/{lesson}', [\App\Http\Controllers\Educator\LessonController::class, 'update']);
            Route::delete('delete/{lesson}', [\App\Http\Controllers\Educator\LessonController::class, 'destroy']);
        });

        Route::get("schudule-management", [\App\Http\Controllers\Educator\ScheduleController::class, "index"])->name("educator.schedule.index");

        Route::prefix("chat")->group(function () {
            Route::get("/", [ChatMessageController::class, "index"])->name("educator.chat.index");


            Route::get('/messages/{chat}', [ChatMessageController::class, 'fetchMessages'])
                ->name('chat.messages');

            // Create or open chat with specific user
            Route::get('/open/{user}', [ChatMessageController::class, 'openChat'])
                ->name('chat.open');

            // Send message
            Route::post('/send', [ChatMessageController::class, 'sendMessage'])
                ->name('chat.send');
        });




        Route::prefix('settings')->group(function () {
            Route::get('/', [ProfileSettingController::class, 'index'])->name('educator.settings');

            // Profile
            Route::get('/profile', [ProfileSettingController::class, 'index'])->name('educator.profile.index');
            Route::post('/profile', [ProfileSettingController::class, 'update'])->name('educator.profile.update');

            // Security
            Route::get('/security', [SecuritySettingController::class, 'index'])->name('educator.security.index');
            Route::post('/security', [SecuritySettingController::class, 'update'])->name('educator.security.update');

            // Payments
            Route::get('/payments', [PaymentSettingController::class, 'index'])->name('educator.payments.index');
            Route::post('/payments', [PaymentSettingController::class, 'update'])->name('educator.payments.update');

            // Payment Methods (CRUD)
            Route::resource('/payment-methods', PaymentMethodController::class)
                ->names('educator.payment_methods')
                ->except(['show', 'edit', 'create']);

            // Availability
            Route::get('/availability', [AvailabilitySettingController::class, 'index'])->name('educator.availability.index');
            Route::post('/availability', [AvailabilitySettingController::class, 'update'])->name('educator.availability.update');

            // Notifications
            Route::get('/notifications', [NotificationSettingController::class, 'index'])->name('educator.notifications.index');
            Route::post('/notifications', [NotificationSettingController::class, 'update'])->name('educator.notifications.update');

            // Privacy
            Route::get('/privacy', [PrivacySettingController::class, 'index'])->name('educator.privacy.index');
            Route::post('/privacy', [PrivacySettingController::class, 'update'])->name('educator.privacy.update');

            // Verification
            Route::get('/verification', [VerificationSettingController::class, 'index'])->name('educator.verification.index');
            Route::post('/verification', [VerificationSettingController::class, 'store'])->name('educator.verification.store');

            // Connections (Google / Zoom / Stripe)
            Route::get('/connections', [ConnectionController::class, 'index'])->name('educator.connections.index');
            Route::post('/connections', [ConnectionController::class, 'update'])->name('educator.connections.update');

            // Preferences
            Route::get('/preferences', [PreferenceController::class, 'index'])->name('educator.preferences.index');
            Route::post('/preferences', [PreferenceController::class, 'update'])->name('educator.preferences.update');
        });
    });

// Student routes
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('profile', [StudentProfileController::class, 'edit'])->name('profile.edit');
        Route::post('profile', [StudentProfileController::class, 'update'])->name('profile.update');

        Route::get('my-courses', [StudentDashboardController::class, 'myCourses'])->name('my-courses');
        Route::get('course-details/{course_id}', [StudentDashboardController::class, 'courseDetails'])->name('course-details');

        Route::get('new-videos', [StudentDashboardController::class, 'newVideos'])->name('new-videos');

        Route::get('analytics', [StudentDashboardController::class, 'analytics'])->name('analytics');

        Route::get('certificates', [StudentDashboardController::class, 'certificates'])->name('certificates');

        Route::get('payments', [StudentDashboardController::class, 'payments'])->name('payments');

        Route::get('wishlist', [StudentDashboardController::class, 'wishlist'])->name('wishlist');
    });

Route::middleware(['guest'])->group(function () {
    Route::get('student/signup', fn() => view('student.signup'))
        ->name('student.signup');
    Route::post('student/signup', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->name('student.signup.store');
});

require __DIR__ . '/auth.php';


Broadcast::routes(['middleware' => ['auth']]);
