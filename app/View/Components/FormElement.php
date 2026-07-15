<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class FormElement extends Component
{
    public $id, $name, $label, $type, $value, $accept, $colSize, $required, $params, $placeholder, $optionValue,
        $optionContent, $optionContentExtra, $optionsExtraFirst, $optionsExtraEnd, $classDiv, $idDiv, $focused,
        $readonly, $classForm, $dataOptions, $defaultTextOption;
    /**
     * Create a new component instance.
     */
    /**
     * @param $id
     * @param $label
     * @param $name
     * @param string $type
     * @param $value
     * @param string $accept
     * @param int $colSize
     * @param bool $required
     * @param object|null $params
     * @param string $placeholder
     * @param string $optionValue
     * @param string $optionContent
     * @param string $optionContentExtra
     * @param array $optionsExtraFirst
     * @param array $optionsExtraEnd
     * @param string $classDiv
     * @param string $idDiv
     * @param bool $focused
     * @param bool $readonly
     * @param string $classForm
     * @param string $dataOptions
     * @param string $defaultTextOption
     */
    public function __construct($id, $label = null, $name = null, $type = 'text', $value = null,
                                $accept = '', $colSize = 12,
                                $required = false, $params = null, $placeholder = '',
                                $optionValue = 'id', $optionContent = '', $optionContentExtra = '',
                                $optionsExtraFirst = [], $optionsExtraEnd = [], $classDiv = '',
                                $idDiv = '', $focused = false, $readonly = false,
                                $classForm = '', $dataOptions = '', $defaultTextOption = 'SELECCIONE UNA OPCIÓN...')
    {
        $this->id = $id;
        //$this->name = $name ?? $id;
        $this->name = isset($name) ? $name : $id;
        //$this->label = $label ?? Str::of($id)->replace(['_id', '_'], ['', ' '])->title();
        $this->label = isset($label) ? $label : Str::of($id)->replace(['_id', '_'], ['', ' '])->title();
        $this->type = $type;
        $this->value = old($id, $value);
        $this->accept = $accept;
        $this->colSize = $colSize;
        $this->required = $required;
        $this->params = $params;
        $this->placeholder = $placeholder;
        $this->optionValue = $optionValue;
        $this->optionContent = $optionContent;
        $this->optionContentExtra = $optionContentExtra;
        $this->optionsExtraFirst = $optionsExtraFirst;
        $this->optionsExtraEnd = $optionsExtraEnd;
        $this->classDiv = $classDiv;
        $this->idDiv = $idDiv;
        $this->focused = $focused;
        $this->readonly = $readonly;
        $this->classForm = $classForm;
        $this->dataOptions = $dataOptions;
        $this->defaultTextOption = $defaultTextOption;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-element');
    }
}
