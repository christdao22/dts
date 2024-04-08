<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class filter extends Component
{
    public $users, $types, $route;
    /**
     * Create a new component instance.
     */
    public function __construct($filters, $route)
    {
        $this->types = $filters['types'];
        $this->users = $filters['users'];
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.filter');
    }
}
