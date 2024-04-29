<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public $input_name, $label, $type, $placeholder, $is_required, $errors, $is_hidden, $small, $arr;

    public function __construct($errors, $data)
    {
        $this->arr = \jsonToPHPArray($data);
        $err = \jsonToPHPArray($errors);
        $this->input_name   = $this->checkIfExist('input_name');
        $this->label        = $this->checkIfExist('label');
        $this->type         = $this->checkIfExist('type');
        $this->placeholder  = $this->checkIfExist('placeholder') && $this->arr['placeholder'] != ''? $this->arr['placeholder'] : 'Enter ' . strtolower($this->label) . '...';
        $this->is_required  = $this->checkIfExist('is_required');
        $this->errors       = $err;
        $this->is_hidden    = $this->checkIfExist('is_hidden') == true? 'd-none' : '';
        $this->small        = $this->checkIfExist('small');
    }

    public function checkIfExist($data) {
        return isset($this->arr[$data])? $this->arr[$data] : '';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.input');
    }
}
