<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Educator\CourseCrudController as EducatorCourseCrudController;
use App\Http\Controllers\Educator\ReviewController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Educator\DashboardController as EducatorDashboardController;
use App\Http\Controllers\Educator\EarningController;
use App\Http\Controllers\Educator\LessonVideoViewsController;
use App\Http\Controllers\Educator\PayoutController;
use App\Http\Controllers\Educator\SessionCallController;
use App\Http\Controllers\Educator\VideoStatController;
use App\Http\Controllers\EducatorController;

use App\Http\Controllers\Educator\Settings\{ProfileSettingController, SecuritySettingController, PaymentSettingController, PaymentMethodController, AvailabilitySettingController, PrivacySettingController, VerificationSettingController, ConnectionController, PreferenceController};
use App\Http\Controllers\EducatorPaymentController;
use App\Http\Controllers\NotificationSettingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\StudentDashboardController;
use App\Mail\OrderInvoiceMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\WatermarkController;
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

Route::get('/', [WebsiteController::class, 'index'])->name('website.index');
Route::get('become-an-educator', [WebsiteController::class, 'educator_signup'])->name('web.eudcator.signup');
Route::post('educator/signup/store', [EducatorController::class, 'store'])->name('educator.signup.store');
Route::get('policies/{slug}', [WebsiteController::class, 'policy'])->name('web.policy');

Route::get('courses', [WebsiteController::class, 'courses'])->name('web.courses');
Route::get('course/{slug}/{id}', [CourseController::class, 'show'])->name('web.course.show');

Route::get('educators', [WebsiteController::class, 'educators'])->name('web.educators.index');
Route::get('educator/{educator}', [WebsiteController::class, 'educator'])->name('web.educator.show');

// API route for fetching educators dynamically

Route::post('loginOrRegister', [LoginController::class, 'loginOrRegister'])->name('web.loginOrRegister');

Route::get('cart', [WebsiteController::class, 'cart'])->name('web.cart');
Route::get('cart/checkout', [CartController::class, 'checkout'])->name('web.cart.checkout');
Route::post('cart/add-to-cart', [CartController::class, 'store'])->name('web.cart.addToCart');
Route::delete('cart/remove-from-cart', [CartController::class, 'remove'])->name('web.cart.removeFromCart');
Route::get('cart/clear', [CartController::class, 'clear'])->name('web.cart.clearCart');
Route::post('/cart/login', [CartController::class, 'loginUserInCartPage'])->name('cart.login');

Route::post('order/add-to-cart', [OrderController::class, 'addToOrderCart'])->name('order.addToOrderCart');
Route::post('order/buy-now', [OrderController::class, 'buyNow'])->name('order.buyNow');
Route::post('order/remove-order-item/', [OrderController::class, 'removeOrderItem'])->name('order.removeOrderItem');

Route::post('/stripe/checkout', [StripeController::class, 'createCheckout']);

Route::get('/payment/success/{order}', [OrderController::class, 'success'])->name('payment.success');

// Success callback from Stripe
Route::get('/stripe/success', [StripeController::class, 'success']);

// Cancel callback from Stripe (optional)
Route::get('/stripe/cancel', [StripeController::class, 'cancel']);

Route::post('paypal/create', [PaypalController::class, 'create'])->name('web.paypal.create');
Route::post('/paypal/capture', [PayPalController::class, 'capture'])->name('paypal.capture');

Route::get('how-it-works', [WebsiteController::class, 'how_it_works'])->name('web.how.it.works');

Route::get('contact-us', [WebsiteController::class, 'contact_us'])->name('web.contact.us');

Route::get('privacy-policy', [WebsiteController::class, 'privacy_policy'])->name('web.privacy.policy');

Route::get('terms-and-conditions', [WebsiteController::class, 'terms_and_conditions'])->name('web.terms.and.conditions');

Route::get('reviews', [WebsiteController::class, 'reviews'])->name('web.reviews');

// Blogs (Browse Content)
Route::get('blogs', [App\Http\Controllers\BlogController::class, 'index'])->name('blogs.index');
Route::get('blogs/{blog:slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blogs.show');

Route::get('faqs', [WebsiteController::class, 'faqs'])->name('web.faqs');
Route::view('community-guidelines', 'website.community-guidelines')->name('web.community.guidelines');
Route::view('safety-and-trust-policy', 'website.safety-and-trust-policy')->name('web.safety.and.trust.policy');
Route::view('student-parent-policy', 'website.student-parent-policy')->name('web.student.parent.policy');
Route::view('about-us', 'website.about-us')->name('web.about-us');
Route::view('privacy-policy', 'website.privacy-policy')->name('web.privacy-policy');
Route::view('terms-and-conditions', 'website.terms-and-conditions')->name('web.terms-and-conditions');
Route::get('educator-policy', [WebsiteController::class, 'educator_policy'])->name('web.educator-policy');
Route::get('refund-policy', [WebsiteController::class, 'refund_policy'])->name('web.refund-policy');
Route::get('cancel-policy', [WebsiteController::class, 'cancel_policy'])->name('web.cancel.policy');
Route::get('user-agreement', [WebsiteController::class, 'user_agreement'])->name('web.user.agreement');

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatMessageController::class, 'index'])->name('chat.index');

        Route::get('/messages/{chat}', [ChatMessageController::class, 'fetchMessages'])->name('chat.messages');

        // Create or open chat with specific user
        Route::get('/open/{user}', [ChatMessageController::class, 'openChat'])->name('chat.open');

        // Send message
        Route::post('/send', [ChatMessageController::class, 'sendMessage'])->name('chat.send');
    });
});

// Admin routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('visual-reports', [App\Http\Controllers\Admin\VisualReportsController::class, 'index'])->name('visual-reports.index');

        // Policies
        Route::resource('policies', App\Http\Controllers\Admin\PolicyController::class)->except(['show']);
        Route::post('policies/{policy}/restore', [App\Http\Controllers\Admin\PolicyController::class, 'restore'])->name('policies.restore');
        // Blogs
        Route::get('blogs', [App\Http\Controllers\Admin\BlogController::class, 'index'])->name('blogs.index');
        Route::get('blogs/create', [App\Http\Controllers\Admin\BlogController::class, 'create'])->name('blogs.create');
        Route::post('blogs', [App\Http\Controllers\Admin\BlogController::class, 'store'])->name('blogs.store');
        Route::get('blogs/{blog}/edit', [App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('blogs.edit');
        Route::put('blogs/{blog}', [App\Http\Controllers\Admin\BlogController::class, 'update'])->name('blogs.update');
        Route::delete('blogs/{blog}', [App\Http\Controllers\Admin\BlogController::class, 'destroy'])->name('blogs.destroy');

        // Admin settings (app + account)
        Route::get('settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings/app', [App\Http\Controllers\Admin\SettingsController::class, 'updateApp'])->name('settings.app.update');
        Route::put('settings/account/profile', [App\Http\Controllers\Admin\SettingsController::class, 'updateAccountProfile'])->name('settings.account.profile');
        Route::put('settings/account/password', [App\Http\Controllers\Admin\SettingsController::class, 'updateAccountPassword'])->name('settings.account.password');

        // Internal app users + roles/permissions (Spatie)
        Route::get('inapp-users', [App\Http\Controllers\Admin\InAppUserManagement::class, 'usersIndex'])->name('inapp-users.index');
        Route::get('inapp-users/create', [App\Http\Controllers\Admin\InAppUserManagement::class, 'usersCreate'])->name('inapp-users.create');
        Route::post('inapp-users', [App\Http\Controllers\Admin\InAppUserManagement::class, 'usersStore'])->name('inapp-users.store');
        Route::get('inapp-users/{user}/edit', [App\Http\Controllers\Admin\InAppUserManagement::class, 'usersEdit'])->name('inapp-users.edit');
        Route::put('inapp-users/{user}', [App\Http\Controllers\Admin\InAppUserManagement::class, 'usersUpdate'])->name('inapp-users.update');
        Route::delete('inapp-users/{user}', [App\Http\Controllers\Admin\InAppUserManagement::class, 'usersDestroy'])->name('inapp-users.destroy');

        Route::get('access-control', [App\Http\Controllers\Admin\InAppUserManagement::class, 'accessControlIndex'])->name('access-control.index');
        Route::post('access-control/roles', [App\Http\Controllers\Admin\InAppUserManagement::class, 'roleStore'])->name('roles.store');
        Route::put('access-control/roles/{role}', [App\Http\Controllers\Admin\InAppUserManagement::class, 'roleUpdate'])->name('roles.update');
        Route::delete('access-control/roles/{role}', [App\Http\Controllers\Admin\InAppUserManagement::class, 'roleDestroy'])->name('roles.destroy');
        Route::post('access-control/permissions', [App\Http\Controllers\Admin\InAppUserManagement::class, 'permissionStore'])->name('permissions.store');
        Route::delete('access-control/permissions/{permission}', [App\Http\Controllers\Admin\InAppUserManagement::class, 'permissionDestroy'])->name('permissions.destroy');

        // Manage Educators
        Route::get('educators', [App\Http\Controllers\Admin\DashboardController::class, 'manageEducators'])->name('manage.educators');

        Route::patch('educators/{id}/status', [App\Http\Controllers\Admin\DashboardController::class, 'updateEducatorStatus'])->name('educators.status');

        Route::delete('educators/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'deleteEducator'])->name('educators.delete');

        Route::get('educators/{id}', [App\Http\Controllers\Admin\EducatorController::class, 'show'])->name('educators.show');

        Route::get('educators/{id}/payouts', [App\Http\Controllers\Admin\EducatorController::class, 'payouts'])->name('educators.payouts');

        Route::get('educators/{id}/courses', [App\Http\Controllers\Admin\EducatorController::class, 'courses'])->name('educators.courses');

        Route::get('educators/{id}/earnings', [App\Http\Controllers\Admin\EducatorController::class, 'earnings'])->name('educators.earnings');

        Route::get('educators/{id}/sessions', [App\Http\Controllers\Admin\EducatorController::class, 'sessions'])->name('educators.sessions');

        // Manage Students
        Route::get('students', [App\Http\Controllers\Admin\DashboardController::class, 'manageStudents'])->name('manage.students');

        Route::get('students/{id}', [App\Http\Controllers\Admin\StudentController::class, 'show'])->name('students.show');

        Route::get('students/{id}/courses', [App\Http\Controllers\Admin\StudentController::class, 'courses'])->name('students.courses');

        Route::get('students/{id}/payments', [App\Http\Controllers\Admin\StudentController::class, 'payments'])->name('students.payments');

        Route::get('students/{id}/sessions', [App\Http\Controllers\Admin\StudentController::class, 'sessions'])->name('students.sessions');

        Route::get('students/{studentId}/sessions/{sessionId}', [App\Http\Controllers\Admin\StudentController::class, 'getSessionDetails'])->name('students.session.details');

        // Additional student actions
        Route::post('students/{id}/reset-password', [App\Http\Controllers\Admin\StudentController::class, 'resetPassword'])->name('students.reset-password');

        Route::get('students/{id}/activity-logs', [App\Http\Controllers\Admin\StudentController::class, 'activityLogs'])->name('students.activity-logs');

        Route::delete('students/{id}', [App\Http\Controllers\Admin\StudentController::class, 'destroy'])->name('students.destroy');

        // Manage Courses
        Route::get('courses', [App\Http\Controllers\Admin\DashboardController::class, 'manageCourses'])->name('manage.courses');

        Route::patch('courses/{id}/status', [App\Http\Controllers\Admin\DashboardController::class, 'updateCourseStatus'])->name('courses.status');

        Route::delete('courses/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'deleteCourse'])->name('courses.delete');

        Route::get('courses/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'showCourse'])->name('courses.show');

        Route::post('courses/{id}/approve', [App\Http\Controllers\Admin\CourseController::class, 'approve'])->name('courses.approve');

        Route::post('courses/{id}/reject', [App\Http\Controllers\Admin\CourseController::class, 'reject'])->name('courses.reject');

        // Manage Lessons
        Route::get('lessons', [App\Http\Controllers\Admin\DashboardController::class, 'manageLessons'])->name('manage.lessons');

        Route::patch('lessons/{id}/status', [App\Http\Controllers\Admin\DashboardController::class, 'updateLessonStatus'])->name('lessons.status');

        Route::get('lessons/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'showLesson'])->name('lessons.show');

        Route::get('payouts', [App\Http\Controllers\Admin\PayoutController::class, 'index'])->name('payouts.index');
        Route::get('payout/{payout}', [App\Http\Controllers\Admin\PayoutController::class, 'show'])->name('payouts.show');
        Route::post('process/payout/{payout}', [App\Http\Controllers\Admin\PayoutController::class, 'process'])->name('payouts.process');

        // Payout generation and management
        Route::get('payouts/upcoming', [App\Http\Controllers\Admin\PayoutController::class, 'upcomingPayouts'])->name('payouts.upcoming');
        Route::post('payouts/generate-upcoming', [App\Http\Controllers\Admin\PayoutController::class, 'generateUpcomingPayouts'])->name('payouts.generate-upcoming');
        Route::post('payouts/release', [App\Http\Controllers\Admin\PayoutController::class, 'releasePayouts'])->name('payouts.release');
        Route::post('payouts/{payout}/release', [App\Http\Controllers\Admin\PayoutController::class, 'releasePayout'])->name('payouts.release-single');
        Route::get('payouts/{payout}/details', [App\Http\Controllers\Admin\PayoutController::class, 'getPayoutDetails'])->name('payouts.details');

        // Additional payout management routes
        Route::patch('earnings/{id}/status', [App\Http\Controllers\Admin\PayoutController::class, 'updateEarningStatus'])->name('earnings.status');
        Route::post('earnings/bulk-update', [App\Http\Controllers\Admin\PayoutController::class, 'bulkUpdateEarnings'])->name('earnings.bulk-update');
        Route::post('payouts/create', [App\Http\Controllers\Admin\PayoutController::class, 'createPayout'])->name('payouts.create');

        Route::resource('earnings', App\Http\controllers\Admin\EarningController::class)->only(['index', 'show']);

        // Financial Reports
        Route::get('financial-reports', [App\Http\Controllers\Admin\FinancialReportsController::class, 'index'])->name('financial-reports.index');
    });

// Educator routes
Route::get('educator-panel/dashboard', [EducatorDashboardController::class, 'index'])->name('educator.dashboard')->middleware(['auth', 'role:educator']);


Route::middleware(['auth', 'role:educator', 'verified', 'educator.profile.verified'])
    ->prefix('educator-panel')
    ->group(function () {
       
        Route::get('/lesson-views/chart', [LessonVideoViewsController::class, 'viewsChart']);

        Route::get('profile', [EducatorDashboardController::class, 'profile'])->name('educator.profile');
        Route::put('profile', [EducatorDashboardController::class, 'profile_update'])->name('educator.profile.update');

        Route::get('video-stats', [VideoStatController::class, 'index'])->name('educator.video-stats.index');

        Route::get('video-stats/{video}', [VideoStatController::class, 'show']);

        Route::get('sessions', [SessionCallController::class, 'index'])->name('educator.sessions.index');
        Route::get('sessions/create', [SessionCallController::class, 'create'])->name('educator.sessions.create');
        Route::post('sessions/store', [SessionCallController::class, 'store'])->name('educator.sessions.store');

        Route::resource('earnings', EarningController::class)
            ->only(['index', 'show'])
            ->names('educator.earnings');

        Route::get('/payments', [EducatorPaymentController::class, 'index'])->name('educator.payments.index');
        Route::get('/payments/{payment}', [EducatorPaymentController::class, 'show'])->name('educator.payments.show');

        Route::get('payouts', [PayoutController::class, 'index'])->name('educator.payouts.index');

        Route::prefix('payouts')->group(function () {
            Route::get('/kpis', [PayoutController::class, 'kpis']);
            Route::get('/upcoming', [PayoutController::class, 'upcoming']);
            Route::get('/history', [PayoutController::class, 'history']);

            Route::get('/banks', [PayoutController::class, 'banks']);
            Route::post('/banks/save', [PayoutController::class, 'saveBank']);
            Route::delete('/banks/{id}', [PayoutController::class, 'deleteBank']);
        });

        Route::resource('courses', \App\Http\Controllers\Educator\CoursesController::class)->names('educator.courses');

        Route::resource('courses-crud', EducatorCourseCrudController::class)->names('educator.courses.crud');
        // Section Management
        Route::post('course-crud/{course}/sections', [EducatorCourseCrudController::class, 'storeSection'])->name('educator.courses.crud.sections.store');
        Route::put('course-crud/sections/{section}', [EducatorCourseCrudController::class, 'updateSection'])->name('educator.courses.crud.sections.update');
        Route::delete('course-crud/sections/{section}', [EducatorCourseCrudController::class, 'destroySection'])->name('educator.courses.crud.sections.destroy');
        // Lesson Management
        Route::post('course-crud/sections/{section}/lessons', [EducatorCourseCrudController::class, 'storeLesson'])->name('educator.courses.crud.sections.lessons.store');
        Route::put('course-crud/lessons/{lesson}', [EducatorCourseCrudController::class, 'updateLesson'])->name('educator.courses.crud.lessons.update');
        Route::delete('course-crud/lessons/{lesson}', [EducatorCourseCrudController::class, 'destroyLesson'])->name('educator.courses.crud.lessons.destroy');

        Route::get('reviews', [ReviewController::class, 'index'])->name('educator.reviews.index');

        Route::post('generate-course-content', [\App\Http\Controllers\OpenAIController::class, 'generateCourseContent'])
            ->name('educator.generate.course.content')
            ->middleware('throttle:5,1');

        Route::get('course/get/sections/{course_id}', [\App\Http\Controllers\Educator\CoursesController::class, 'course_sections'])->middleware('api.auth');
        Route::post('courses/section/{course_id}', [\App\Http\Controllers\Educator\CoursesController::class, 'post_course_section'])->middleware('api.auth');
        Route::delete('courses/section/{section_id}', [\App\Http\Controllers\Educator\CoursesController::class, 'delete_course_section'])
            ->name('educator.courses.section.delete')
            ->middleware('api.auth');
        Route::get('course/get/section/{section_id}', [\App\Http\Controllers\Educator\CoursesController::class, 'get_course_section'])
            ->name('educator.courses.section.get')
            ->middleware('api.auth');
        Route::post('course/update/section/{section_id}', [\App\Http\Controllers\Educator\CoursesController::class, 'update_course_section'])
            ->name('educator.courses.section.update')
            ->middleware('api.auth');

        Route::prefix('lessons')->group(function () {
            Route::post('/store', [\App\Http\Controllers\Educator\LessonController::class, 'store']);
            Route::put('update/{lesson}', [\App\Http\Controllers\Educator\LessonController::class, 'update']);
            Route::delete('delete/{lesson}', [\App\Http\Controllers\Educator\LessonController::class, 'destroy']);
        });

        Route::get('schudule-management', [\App\Http\Controllers\Educator\ScheduleController::class, 'index'])->name('educator.schedule.index');

        Route::get('resources', [EducatorDashboardController::class, 'resources'])->name('educator.resources.index');

        Route::prefix('settings')->group(function () {
            Route::get('/', [ProfileSettingController::class, 'index'])->name('educator.settings');

            // Profile
            Route::get('/profile', [ProfileSettingController::class, 'index'])->name('educator.profile.index');
            Route::post('/profile', [ProfileSettingController::class, 'update'])->name('educator.profile.update');

            // Security
            Route::get('/security', [SecuritySettingController::class, 'index'])->name('educator.security.index');
            Route::post('/security', [SecuritySettingController::class, 'update'])->name('educator.security.update');

            // Payments
            Route::get('/payments', [PaymentSettingController::class, 'index'])->name('educator.settings.payments.index');
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
        Route::post('/userprofile', [StudentProfileController::class, 'updateProfile'])->name('UserProfile.update');
        Route::post('profile/account', [StudentProfileController::class, 'updateAccount'])->name('profile.updateAccount');
        Route::post('profile/password', [StudentProfileController::class, 'updatePassword'])->name('profile.updatePassword');
        Route::post('profile/notifications', [StudentProfileController::class, 'updateNotifications'])->name('profile.updateNotifications');

        Route::get('my-courses', [StudentDashboardController::class, 'myCourses'])->name('my-courses');

        Route::get('course-details/{course_id}/{lesson_id?}', [StudentDashboardController::class, 'courseDetails'])->name('course_details');
        Route::get('lesson/{course}/{lesson}', [StudentDashboardController::class, 'lessonDetails'])->name('lesson-details');

        Route::get('new-videos', [StudentDashboardController::class, 'newVideos'])->name('new-videos');

        Route::get('analytics', [StudentDashboardController::class, 'analytics'])->name('analytics');

        Route::get('certificates', [StudentDashboardController::class, 'certificates'])->name('certificates');

        Route::get('payments', [StudentDashboardController::class, 'payments'])->name('payments');

        Route::get('wishlist', [StudentDashboardController::class, 'wishlist'])->name('wishlist');
        Route::delete('wishlist/{course_id}', [StudentDashboardController::class, 'removeWishlistCourse'])->name('wishlist.remove');

        Route::post('lesson-comment', [StudentDashboardController::class, 'storeLessonComment'])->name('lesson_comment.store');
    });

Route::post('book-session', [WebsiteController::class, 'bookSession'])->name('book.session');

Route::middleware(['guest'])->group(function () {
    Route::get('student/signup', fn() => view('student.signup'))->name('student.signup');
    Route::post('student/signup', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->name('student.signup.store');
});

require __DIR__ . '/auth.php';

Broadcast::routes(['middleware' => ['auth']]);

if (app()->environment('local')) {
    Route::get('/mail/preview/invoice', function () {
        $order = Order::find(1991);

        return new OrderInvoiceMail($order);
    });

    // Debug route to check and set admin role
    Route::get('/debug/set-admin/{email}', function ($email) {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return "User with email {$email} not found";
        }

        $user->update(['role' => 'admin']);
        return "User {$email} role set to admin. Current role: {$user->fresh()->role}";
    });

    Route::get('/debug/check-user/{email}', function ($email) {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return "User with email {$email} not found";
        }

        return [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'isAdmin' => $user->isAdmin(),
            'isStudent' => $user->isStudent(),
            'isEducator' => $user->isEducator(),
        ];
    });
}

Route::view('watermark-pdf', 'watermark-pdf');
Route::post('/watermark-pdf', [WatermarkController::class, 'applyWatermark']);
