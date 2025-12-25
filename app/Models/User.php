<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'education',
        'interests',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['full_name'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEducator()
    {
        return $this->role === 'educator';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    // Add hasOne relationship to StudentProfile
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function educatorProfile()
    {
        return $this->hasOne(EducatorProfile::class);
    }

    public function guardian()
    {
        return $this->hasOne(Guardian::class, 'student_id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'educator_id');
    }


    public function earnings()
    {
        return $this->hasMany(Earning::class, 'educator_id');
    }

    public function educatorReviews()
    {
        return $this->hasMany(\App\Models\EducatorReview::class, 'educator_id');
    }




    // educator profile settings
    /**
     * Profile Settings
     */
    public function profileSetting()
    {
        return $this->hasOne(\App\Models\Educator\ProfileSetting::class, 'user_id');
    }

    /**
     * Security Settings (password/2FA)
     */
    public function securitySetting()
    {
        return $this->hasOne(\App\Models\Educator\SecuritySetting::class, 'educator_id');
    }

    /**
     * Payment Settings
     */
    public function paymentSetting()
    {
        return $this->hasOne(\App\Models\Educator\PaymentSetting::class, 'educator_id');
    }

    /**
     * Payment Methods (multiple)
     */
    public function paymentMethods()
    {
        return $this->hasMany(\App\Models\Educator\PaymentMethod::class, 'educator_id');
    }

    /**
     * Availability
     */
    public function availability()
    {
        return $this->hasOne(\App\Models\Educator\AvailabilitySetting::class, 'educator_id');
    }

    /**
     * Notification Settings
     */
    public function notificationSetting()
    {
        return $this->hasOne(\App\Models\NotificationSetting::class, 'user_id');
    }

    /**
     * Privacy Settings
     */
    public function privacy()
    {
        return $this->hasOne(\App\Models\Educator\PrivacySetting::class, 'educator_id');
    }

    /**
     * Verification (ID, credentials)
     */
    public function verification()
    {
        return $this->hasOne(\App\Models\Educator\VerificationSetting::class, 'educator_id');
    }

    /**
     * Connections (Google, Zoom, Stripe)
     */
    public function connections()
    {
        return $this->hasOne(\App\Models\Educator\Connection::class, 'educator_id');
    }

    /**
     * Preferences (language, theme, time format)
     */
    public function preferences()
    {
        return $this->hasOne(\App\Models\Educator\Preference::class, 'educator_id');
    }



    public function getnameInitialsattribute()
    {
        return ucfirst(substr($this->first_name, 0, 1)) . ucfirst(substr($this->last_name, 0, 1));
    }



    public function myStudents()
    {
        return $this->hasManyThrough(
            User::class,           // final model (students)
            CoursePurchase::class, // intermediate model
            'educator_id',         // foreign key on course_purchases to educator
            'id',                  // local key on users table for student
            'id',                  // educator id on users table
            'student_id'           // student id on course_purchases table
        )->distinct();
    }

    public function scopeVerifiedEducator($query)
    {
        return $query->where('role', 'educator')
            ->whereNotNull('email_verified_at');
    }


    public function myPurchasedCourses()
    {
        return $this->belongsToMany(
            Course::class,
            'course_purchases',
            'student_id',   // foreign key on course_purchases pointing to student
            'course_id'     // foreign key on course_purchases pointing to course
        )
            ->withPivot(['price', 'payment_status', 'purchase_date'])
            ->withTimestamps();
    }


    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }





    public function purchases()
    {
        return $this->hasMany(UserPurchasedItem::class);
    }
    public function purchasedCourses()
    {
        return $this->purchases()
            ->where('purchasable_type', Course::class);
    }

    public function purchasedLessons()
    {
        return $this->purchases()
            ->where('purchasable_type', Lesson::class);
    }
}
