<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ActionButtons extends Component
{
    public $routeName, $params;

    /**
     * @param string $routeName
     * @param object|null $params
     */
    public function __construct($routeName = '', object $params)
    {
        $this->routeName = $routeName;
        $this->params = $params;
    }
    /**
     * Create a new component instance.
     */


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.action-buttons');
    }
}
