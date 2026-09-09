<svg class="admin-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
    @case('home')
        <path d="m3 10 9-8 9 8v12H3Z"/>
        <path d="M9 22V12h6v10"/>
        @break
    @case('clip')
        <path d="m9 16 9-9a3 3 0 0 0-4-4L4 13a5 5 0 0 0 7 7L21 10M7 14l8-8a1 1 0 0 1 2 2l-8 8"/>
        @break
    @case('monitor')
        <path d="M3 8V4h18v15h-7M3 13a6 6 0 0 1 6 6M3 17a2 2 0 0 1 2 2"/>
        <circle cx="3" cy="21" r=".5"/>
        @break
    @case('chart')
        <path d="M5 20v-5m7 5V9m7 11V3"/>
        @break
    @case('archive')
        <path d="M3 8h18V3H3Zm2 0v13h14V8M9 12h6"/>
        @break
    @case('book')
        <path d="M5 2h15v20H5a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm-2 16h17"/>
        @break
    @case('settings')
        <path d="m9 3 1-2h4l1 2 3 2 2 1-1 3 2 2v3l-2 1-1 3-3 1-1 3h-4l-1-3-3-1-2-2 1-3-2-2V9l3-1 1-3Z"/>
        <circle cx="12" cy="12" r="3"/>
        @break
    @case('warning')
        <path d="M10 3 2 19a2 2 0 0 0 2 3h16a2 2 0 0 0 2-3L14 3a2 2 0 0 0-4 0Z"/>
        <path d="M12 8v6m0 4h.01"/>
        @break
    @case('chip')
        <rect x="5" y="5" width="14" height="14" rx="2"/>
        <path d="M9 9h6v6H9Zm0-7v3m6-3v3m-6 14v3m6-3v3M2 9h3m-3 6h3m14-6h3m-3 6h3"/>
        @break
    @case('headset')
        <path d="M3 14v-2a9 9 0 0 1 18 0v2"/>
        <rect x="3" y="12" width="5" height="9" rx="2"/>
        <rect x="16" y="12" width="5" height="9" rx="2"/>
        @break
    @case('search')
        <circle cx="10" cy="10" r="7"/>
        <path d="m15 15 6 6"/>
        @break
    @case('exit')
        <path d="M9 3H3v18h6m5-14 5 5-5 5m-6-5h13"/>
        @break
    @endswitch
</svg>
