<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class nav extends Component
{
    public $items;

    public $active;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($context = 'side')
    {
        $this->items = $this->prepareItems(config('nav'));
        // dd($this->items);
        $this->active = Route::currentRouteName();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.nav');
    }

    protected function prepareItems($items){
        $user = auth()->user();

        foreach($items as $key => $item){
            if(isset($item['ability']) && !$user->can($item['ability'])){
                unset($items[$key]);
            }
        }
            return $items;
    }
}
