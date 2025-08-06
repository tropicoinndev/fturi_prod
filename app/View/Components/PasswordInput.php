<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PasswordInput extends Component
{
    public $id;
    public $name;
    public $class;
    public $placeholder;

    public function __construct($id, $name, $class = '', $placeholder = 'Ingrese su contraseña')
    {
        $this->id = $id;
        $this->name = $name;
        $this->class = $class;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.password-input');
    }
}
