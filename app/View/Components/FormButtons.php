<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormButtons extends Component
{
    public $routeName, $isEdit;

    /**
     * Create a new component instance.
     */
    /**
     * @param string $routeName
     * @param bool $isEdit
     */
    public function __construct($routeName = '', $isEdit = false)
    {
        $this->routeName = $routeName;
        $this->isEdit = $isEdit;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-buttons');
    }
}
