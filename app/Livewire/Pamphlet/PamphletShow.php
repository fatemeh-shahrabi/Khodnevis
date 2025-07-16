<?php

namespace App\Livewire\Pamphlet;

use Livewire\Component;
use App\Models\Pamphlet;

class PamphletShow extends Component
{
    public $pamphlet;

    public function mount($pamphletId)
    {
        $this->pamphlet = Pamphlet::findOrFail($pamphletId);
    }

    public function deletePamphlet()
    {
        $this->pamphlet->delete();

        session()->flash('message', 'جزوه با موفقیت حذف شد.');
        return redirect()->route('pamphlet.history');
    }

    public function render()
    {
        return view('livewire.pamphlet.pamphlet-show');
    }
}
