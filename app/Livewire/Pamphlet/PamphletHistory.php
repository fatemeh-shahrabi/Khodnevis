<?php

namespace App\Livewire\Pamphlet;

use Livewire\Component;
use App\Models\Pamphlet;
use Illuminate\Support\Facades\Auth;

class PamphletHistory extends Component
{
    public $pamphlets;

    public function mount()
    {
        $this->loadPamphlets();
    }

    protected function loadPamphlets()
    {
        $this->pamphlets = Pamphlet::where('user_id', Auth::id())->latest()->get();
    }

    public function deletePamphlet($pamphletId)
    {
        $pamphlet = Pamphlet::findOrFail($pamphletId);
        $pamphlet->delete();

        $this->loadPamphlets();
        session()->flash('message', 'جزوه با موفقیت حذف شد');
    }

    public function render()
    {
        return view('livewire.pamphlet.pamphlet-history');
    }
}
