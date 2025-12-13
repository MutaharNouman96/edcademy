<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DynamicLayout extends Component
{
    public string $layout;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $user = auth()->user();

        $this->layout = match ($user?->role) {
            'educator' => 'educator-layout',
            'student'  => 'student-layout',
            'admin'    => 'admin-layout',
            default    => 'app-layout',
        };
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dynamic-layout');
    }
}
