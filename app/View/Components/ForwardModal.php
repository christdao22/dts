<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ForwardModal extends Component
{
    public $documentTracking, $terminals, $routeName;

    public function __construct($documentTracking, $terminals, $routeName)
    {
        $this->documentTracking = $documentTracking;
        $this->terminals = $terminals;
        $this->routeName = $routeName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forward-modal');
    }
}
