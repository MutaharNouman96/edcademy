<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Chat extends Component
{
    public $chats;
    public $activeChatId;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($chats, $activeChatId)
    {
        //
        $this->chats = $chats;
        $this->activeChatId = $activeChatId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.chat');
    }
}
