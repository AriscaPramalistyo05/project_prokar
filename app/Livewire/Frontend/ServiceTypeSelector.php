<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class ServiceTypeSelector extends Component
{
    public $activeType = 'datang';

    public function selectType($type)
    {
        $this->activeType = $type;
        $this->dispatch('serviceTypeChanged', type: $type);
    }

    public function render()
    {
        return view('livewire.frontend.service-type-selector');
    }
}
