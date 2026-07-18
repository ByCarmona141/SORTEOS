<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class StatCard extends Component {
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $label,
        public string $value,
        public ?string $hint = null,
        public bool $highlight = false // true = usa el "halo dorado"
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.ui.stat-card');
    }
}
