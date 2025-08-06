<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\cajas_users;
use App\Models\cajas;

class Sidebar extends Component
{
     public $cajas;
     public $cajas_users;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cajas = cajas::all();
        $this->cajas_users = cajas_users::all();
          
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sidebar');
    }
}
