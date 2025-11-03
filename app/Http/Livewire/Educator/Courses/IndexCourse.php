<?php

namespace App\Http\Livewire\Educator\Courses;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Course;

class IndexCourse extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterType = '';
    public $filterSubject = '';
    public $confirmingDelete = false;
    public $deleteId = null;
    public $deleteTitle = '';

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['refreshCourses' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
    public function updatingFilterType()
    {
        $this->resetPage();
    }
    public function updatingFilterSubject()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterType = '';
        $this->filterSubject = '';
    }

    public function confirmDelete($id)
    {
        $course = Course::findOrFail($id);
        $this->deleteId = $course->id;
        $this->deleteTitle = $course->title;
        $this->dispatchBrowserEvent('show-delete-modal');
    }

    public function deleteCourse()
    {
        $course = Course::find($this->deleteId);
        if ($course) {
            $course->delete();
        }

        $this->dispatchBrowserEvent('hide-delete-modal');
        session()->flash('message', 'Course deleted successfully!');
        $this->reset(['deleteId', 'deleteTitle']);
    }

    public function render()
    {
        $query = Course::where('user_id', auth()->id());

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterSubject) {
            $query->where('subject', 'like', "%{$this->filterSubject}%");
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('subject', 'like', "%{$this->search}%");
            });
        }

        $courses = $query->latest()->paginate(10);

        return view("livewire.educator.courses.index-course", [
            'courses' => $courses
        ])->layout('layouts.educator');
    }
}
