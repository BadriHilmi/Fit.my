<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AuthLayout extends Component
{
    public function __construct(
        public string $title = 'Account — Fit.my',
        public ?string $heading = null,
        public ?string $subheading = null,
        public string $maxWidth = 'max-w-md',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('layouts.auth');
    }
}
