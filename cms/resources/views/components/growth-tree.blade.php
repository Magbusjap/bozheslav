@props([
    'variant' => 'skills',
])

@php
    $isExperience = $variant === 'experience';
    $label = $isExperience ? __('experience-blade.tree.aria') : __('skills-blade.tree.aria');
@endphp

<div class="growth-tree growth-tree--{{ $variant }}" aria-label="{{ $label }}">
    @if ($isExperience)
        <svg class="growth-tree__roots" viewBox="0 0 960 120" role="img" aria-hidden="true">
            <path class="growth-tree__root-line" d="M0 68 C120 18 220 108 340 58 S560 20 700 68 S850 115 960 42" />
            <path class="growth-tree__root-line growth-tree__root-line--thin" d="M0 92 C170 50 260 104 420 82 S680 46 960 86" />
            <path class="growth-tree__root-line growth-tree__root-line--thin" d="M0 42 C95 66 160 34 245 50 S355 88 440 55" />
        </svg>
    @else
        <div class="growth-tree__stage">
            <svg class="growth-tree__illustration" viewBox="0 0 760 340" role="img" aria-hidden="true">
                <path class="growth-tree__ground" d="M62 274 C160 246 244 294 344 266 S556 240 698 270" />
                <path class="growth-tree__root-line" d="M334 266 C430 286 500 300 580 282 S700 240 760 258" />
                <path class="growth-tree__root-line growth-tree__root-line--thin" d="M328 272 C430 328 548 324 674 306" />
                <path class="growth-tree__root-line growth-tree__root-line--thin" d="M326 270 C246 304 156 318 82 298" />
                <path class="growth-tree__trunk" d="M326 268 C310 226 314 188 330 146 C344 108 350 78 334 45 C378 82 390 126 382 162 C374 202 360 231 374 268 Z" />
                <path class="growth-tree__branch" d="M350 142 C292 116 238 112 182 138" />
                <path class="growth-tree__branch" d="M366 158 C424 118 482 96 560 100" />
                <path class="growth-tree__branch growth-tree__branch--small" d="M348 113 C310 88 280 64 264 28" />
                <path class="growth-tree__branch growth-tree__branch--small" d="M378 128 C420 84 456 58 510 44" />
                <path class="growth-tree__leaf" d="M154 124 C174 92 226 98 240 134 C218 154 176 154 154 124 Z" />
                <path class="growth-tree__leaf" d="M530 82 C564 52 626 70 632 116 C596 136 548 124 530 82 Z" />
                <path class="growth-tree__leaf" d="M242 18 C276 -6 318 14 320 50 C290 68 252 54 242 18 Z" />
                <path class="growth-tree__leaf" d="M492 34 C520 4 572 12 584 52 C554 74 512 68 492 34 Z" />
                <path class="growth-tree__face" d="M336 174 C348 182 364 182 376 174" />
                <path class="growth-tree__eye" d="M330 150 C338 156 346 156 354 150" />
                <path class="growth-tree__eye" d="M362 150 C370 156 378 156 386 150" />
            </svg>
            <p class="growth-tree__caption">{{ __('skills-blade.tree.caption') }}</p>
        </div>
    @endif
</div>
