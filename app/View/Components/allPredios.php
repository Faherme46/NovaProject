<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use App\Models\Predio;

class AllPredios extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        )
    {    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $allPredios=Predio::all();
        return view('components.all-predios',compact('allPredios'));
    }
}
