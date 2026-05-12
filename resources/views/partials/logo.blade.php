@php($variant = $variant ?? 'dark')
<span class="brand-logo brand-logo--{{ $variant }}" aria-hidden="true">
    <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" focusable="false">
        <defs>
            <linearGradient id="logo-bg-{{ $variant }}" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="#0a1832"/>
                <stop offset="1" stop-color="#1c3060"/>
            </linearGradient>
            <linearGradient id="logo-gold-{{ $variant }}" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="#e1b56e"/>
                <stop offset="1" stop-color="#a47a36"/>
            </linearGradient>
        </defs>
        <rect width="64" height="64" rx="14" fill="url(#logo-bg-{{ $variant }})"/>
        <path d="M48 14 L12 30 L25 34 L29 47 L34 38 L48 14 Z" fill="url(#logo-gold-{{ $variant }})"/>
        <path d="M29 47 L34 38 L25 34 Z" fill="#8d6224" opacity="0.7"/>
    </svg>
</span>
