<?php

namespace App\Http\Livewire\Plan;

use App\Models\Plan;
use App\Services\Pagseguro\Plan\PlanCreateService;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class PlanCreate extends Component
{
    public array $plan = [];

    protected $rules = [
        'plan.name' => 'required',
        'plan.description' => 'required',
        'plan.price' => 'required',
        'plan.slug' => 'required'
    ];

    public function createPlan()
    {
        $this->validate();

        $plan = $this->plan;

        $planPagSeguroReference = (new PlanCreateService())->makeRequest($plan);
        $plan['reference'] = $planPagSeguroReference;

        Plan::create($plan);

        $this->plan = [];
            
        session()->flash('message', 'Plano criado com sucesso');
    }

    public function render()
    {
        return view('livewire.plan.plan-create');
    }
}
