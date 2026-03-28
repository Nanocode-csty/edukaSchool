<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Field extends Component
{
    public $name;
    public $label;
    public $type;
    public $options;

    public function __construct(
        $name,
        $label,
        $type = 'text',
        $options = []
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->options = $options;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.field');
    }
}
