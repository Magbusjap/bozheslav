@props([
    'variant' => 'skills',
])

<div class="living-bird living-bird--{{ $variant }}" aria-hidden="true">
    <svg class="living-bird__svg" viewBox="0 0 170 128">
        <path class="living-bird__flight-ring" d="M25 70 C44 18 109 15 130 62" />
        <g class="living-bird__body">
            <path class="living-bird__tail" d="M39 74 L9 80 L38 91 Z" />
            <path class="living-bird__tail living-bird__tail--blue" d="M50 82 L18 108 L58 95 Z" />
            <ellipse class="living-bird__belly" cx="84" cy="68" rx="43" ry="37" />
            <path class="living-bird__back" d="M46 62 C60 24 113 20 128 61 C110 47 84 45 65 60 C58 65 51 66 46 62 Z" />
            <path class="living-bird__wing living-bird__wing--front" d="M61 70 C80 52 107 55 119 77 C101 91 76 92 61 70 Z" />
            <path class="living-bird__wing-stripe" d="M76 81 C87 73 100 72 111 78" />
            <g class="living-bird__head">
                <circle class="living-bird__head-fill" cx="109" cy="44" r="24" />
                <path class="living-bird__cheek" d="M92 50 C102 38 119 38 130 50 C121 65 102 66 92 50 Z" />
                <path class="living-bird__beak" d="M130 48 L160 56 L130 64 Z" />
                <circle class="living-bird__eye" cx="116" cy="40" r="4.6" />
                <circle class="living-bird__eye-shine" cx="117.6" cy="38.4" r="1.4" />
            </g>
            <path class="living-bird__leg" d="M78 100 L72 118" />
            <path class="living-bird__leg" d="M96 99 L102 118" />
            <path class="living-bird__claw" d="M66 118 C72 114 78 115 82 120" />
            <path class="living-bird__claw" d="M96 120 C102 114 109 115 114 119" />
        </g>
    </svg>
</div>
