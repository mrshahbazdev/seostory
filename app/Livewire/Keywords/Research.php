<?php

namespace App\Livewire\Keywords;

use Livewire\Component;
use App\Services\KeywordResearchService;
use Illuminate\Http\Request;

class Research extends Component
{
    public $term = '';
    public $data = null;
    public $loading = false;

    // Support query string to allow direct linking
    protected $queryString = ['term'];

    public function mount(Request $request)
    {
        $this->term = $request->query('term', '');

        // Auto-search if term is present on load
        if ($this->term) {
            $this->analyze();
        }
    }

    public function analyze()
    {
        if (empty($this->term))
            return;

        $this->loading = true;

        $service = new KeywordResearchService(); // Direct instantiation since it's a simple service
        $this->data = $service->analyze($this->term);

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.keywords.research')->layout('layouts.app');
    }
}
