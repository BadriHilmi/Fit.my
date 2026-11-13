<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PublicLayout extends Component
{
    public function __construct(
        public string $title = 'Fit.my — Daily Calorie & Meal Tracking',
        public string $description = 'Calculate your personalized daily calorie goal, log breakfast, lunch, and dinner, and review daily summaries with Fit.my.',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('layouts.public');
    }
}
