<?php



namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any courses.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the course.
     */
    public function view(User $user, Course $course)
    {
        // Allow if course is published or user is the educator
        return $course->status === 'published' || $course->user_id === $user->id;
    }

    /**
     * Determine whether the user can create courses.
     */
    public function create(User $user)
    {
        return true; // Or add role check: $user->hasRole('educator')
    }

    /**
     * Determine whether the user can update the course.
     */
    public function update(User $user, Course $course)
    {
        return $course->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the course.
     */
    public function delete(User $user, Course $course)
    {
        return $course->user_id === $user->id;
    }
}
