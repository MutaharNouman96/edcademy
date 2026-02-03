<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentCourseEnrollmentMail extends Mailable implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public Order $order;
    public $enrolledCourses;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $enrolledCourses = null)
    {
        $this->order = $order;
        $this->enrolledCourses = $enrolledCourses ?: collect();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Your New Course - Ed-Cademy',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.student-course-enrollment',
            with: [
                'order' => $this->order,
                'student' => $this->order->user,
                'enrolledCourses' => $this->enrolledCourses,
                'orderItems' => $this->order->items,
            ],
        );
    }
}