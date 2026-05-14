@props([
    'variant' => 'skills',
])

@php
    $isExperience = $variant === 'experience';
    $label = $isExperience ? __('experience-blade.tree.aria') : __('skills-blade.tree.aria');
@endphp

<div class="growth-tree growth-tree--{{ $variant }}" aria-label="{{ $label }}">
    @if ($isExperience)
        <div class="growth-tree__trail growth-tree__trail--experience" aria-hidden="true">
            <svg class="growth-tree__trail-svg" viewBox="0 0 1280 150" preserveAspectRatio="none">
                <path class="growth-tree__path-line" d="M0 104 C150 72 250 122 380 92 S610 48 770 92 S1000 128 1280 86" />
                <path class="growth-tree__path-line growth-tree__path-line--thin" d="M0 124 C210 86 330 134 540 110 S880 72 1280 116" />
            </svg>
            <svg class="growth-tree__python-figure growth-tree__python-figure--experience" viewBox="0 0 190 46">
                <path class="growth-tree__python-body" d="M6 26 C30 6 66 7 94 24 S142 39 176 19" />
                <path class="growth-tree__python-belly" d="M24 26 C48 14 72 15 96 27" />
                <ellipse class="growth-tree__python-head" cx="180" cy="18" rx="15" ry="11" />
                <circle class="growth-tree__python-eye" cx="185" cy="15" r="2" />
                <path class="growth-tree__python-smile" d="M185 23 C188 25 192 24 195 21" />
            </svg>
        </div>
    @else
        <div class="growth-tree__trail growth-tree__trail--skills" aria-hidden="true">
            <svg class="growth-tree__trail-svg" viewBox="0 0 1100 190" preserveAspectRatio="none">
                <path class="growth-tree__path-line" d="M0 154 C120 130 250 174 374 145 S598 120 742 148 S952 176 1100 132" />
                <path class="growth-tree__path-line growth-tree__path-line--thin" d="M0 178 C170 156 282 184 420 166 S654 140 802 168 S1000 182 1100 160" />
            </svg>
            <svg class="growth-tree__python-figure growth-tree__python-figure--skills" viewBox="0 0 170 42">
                <path class="growth-tree__python-body" d="M5 24 C26 8 58 8 82 23 S124 34 156 17" />
                <path class="growth-tree__python-belly" d="M22 24 C42 14 62 15 83 25" />
                <ellipse class="growth-tree__python-head" cx="160" cy="16" rx="13" ry="10" />
                <circle class="growth-tree__python-eye" cx="164" cy="13" r="1.8" />
                <path class="growth-tree__python-smile" d="M164 20 C167 22 171 21 174 18" />
            </svg>
        </div>
        <div class="growth-tree__stage">
            <svg class="growth-tree__sapling" viewBox="0 0 260 285" role="img" aria-hidden="true">
                <ellipse class="growth-tree__shadow" cx="130" cy="253" rx="92" ry="18" />
                <path class="growth-tree__ground" d="M36 250 C74 230 112 261 154 244 S218 238 240 250" />
                <path class="growth-tree__trunk" d="M111 248 C89 196 93 132 120 82 C134 57 139 35 127 15 C167 44 184 86 175 126 C165 172 148 199 157 248 Z" />
                <path class="growth-tree__trunk-mark" d="M121 205 C128 199 137 199 143 206" />
                <path class="growth-tree__trunk-mark" d="M130 160 C136 153 146 154 151 162" />
                <g class="growth-tree__sway">
                    <path class="growth-tree__branch" d="M130 124 C94 95 54 90 25 111" />
                    <path class="growth-tree__branch" d="M154 137 C190 104 221 94 251 104" />
                    <path class="growth-tree__branch growth-tree__branch--small" d="M141 92 C117 68 104 48 101 22" />
                    <path class="growth-tree__branch growth-tree__branch--small" d="M160 101 C183 68 208 52 237 48" />
                    <path class="growth-tree__leaf growth-tree__leaf--light" d="M10 97 C28 52 82 52 99 92 C80 125 32 124 10 97 Z" />
                    <path class="growth-tree__leaf growth-tree__leaf--main" d="M64 53 C76 5 145 -8 171 36 C158 83 100 102 64 53 Z" />
                    <path class="growth-tree__leaf growth-tree__leaf--main" d="M142 37 C177 -2 239 16 249 68 C212 95 159 84 142 37 Z" />
                    <path class="growth-tree__leaf growth-tree__leaf--dark" d="M177 92 C214 57 273 77 276 129 C235 155 190 137 177 92 Z" />
                    <path class="growth-tree__leaf growth-tree__leaf--dark" d="M98 98 C137 65 198 85 204 138 C166 164 113 151 98 98 Z" />
                    <path class="growth-tree__leaf-shine" d="M83 41 C101 22 133 21 151 42" />
                    <path class="growth-tree__leaf-shine" d="M169 62 C188 47 214 49 231 68" />
                </g>
            </svg>
        </div>
        <x-living-bird variant="skills" />
    @endif
</div>
