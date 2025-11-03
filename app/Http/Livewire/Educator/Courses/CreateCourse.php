<?php

namespace App\Http\Livewire\Educator\Courses;

use Livewire\Component;

use Livewire\WithFileUploads;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\CourseFeature;

class CreateCourse extends Component
{
    use WithFileUploads;

    public $title, $description, $subject, $level, $price, $duration, $difficulty;
    public $type = 'module';
    public $tags, $publish_option = 'draft', $publish_date;
    public $thumbnail;
    public $features = [
        'drip_release' => false,
        'certificate' => true,
        'quizzes' => false,
        'downloads' => false,
    ];
    public $lessons = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'subject' => 'required|string',
        'level' => 'required|string',
        'price' => 'required|numeric|min:0',
    ];

    public function render()
    {
        return view('livewire.educator.courses.create-course')->layout('layouts.educator');
    }



    public function addLesson()
    {
        $this->lessons[] = [
            'name' => '',
            'category' => '',
            'video_link' => '',
            'description' => '',
            'duration' => '',
            'is_preview' => false,
        ];
    }

    public function removeLesson($index)
    {
        unset($this->lessons[$index]);
        $this->lessons = array_values($this->lessons);
    }

    public function saveCourse($status = 'draft')
    {
        $this->validate();

        $path = $this->thumbnail ? $this->thumbnail->store('thumbnails', 'public') : null;

        $course = Course::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'subject' => $this->subject,
            'level' => $this->level,
            'price' => $this->price,
            'duration' => $this->duration,
            'difficulty' => $this->difficulty,
            'type' => $this->type,
            'tags' => $this->tags,
            'thumbnail' => $path,
            'publish_option' => $this->publish_option,
            'publish_date' => $this->publish_date,
            'status' => $status,
        ]);

        foreach ($this->lessons as $i => $lesson) {
            $course->lessons()->create([
                'name' => $lesson['name'],
                'category' => $lesson['category'],
                'video_link' => $lesson['video_link'],
                'description' => $lesson['description'],
                'duration' => $lesson['duration'],
                'is_preview' => $lesson['is_preview'],
                'order' => $i + 1,
            ]);
        }

        $course->features()->create($this->features);

        session()->flash('success', 'Course saved successfully! Add Course Details for each of the course sections.');
        return redirect()->route('educator.courses');
    }
}
