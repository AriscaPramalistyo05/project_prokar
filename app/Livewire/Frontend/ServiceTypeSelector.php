<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class ServiceTypeSelector extends Component
{
    public $activeType = 'datang';

    public function selectType($type)
    {
        $this->activeType = in_array($type, ['datang', 'kirim']) ? $type : 'datang';
        $this->dispatch('serviceTypeChanged', type: $this->activeType);
    }

    public function render()
    {
        return view('livewire.frontend.service-type-selector');
    }
}
