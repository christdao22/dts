<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public $input_name, $label, $type, $placeholder, $is_required, $errors, $is_hidden;

    public function __construct($errors, $data)
    {
        $arr = \jsonToPHPArray($data);
        $err = \jsonToPHPArray($errors);
        $this->input_name   = $arr['input_name'];
        $this->label        = $arr['label'];
        $this->type         = $arr['type'];
        $this->placeholder  = isset($arr['placeholder']) && $arr['placeholder']!==null ? $arr['placeholder'] : 'Enter ' . strtolower($arr['label']) . '...';
        $this->is_required  = $arr['is_required'];
        $this->errors       = $err;
        $this->is_hidden    = isset($arr['is_hidden']) && $arr['is_hidden']==true? 'd-none' : '';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.input');
    }
}
