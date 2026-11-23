{{-- The admin panel: the template of fida-sarafi (sidebar, topbar, search, tab bar), in Rest green. --}}
@php
    $user = auth()->user();
    $ui = fn (string $path) => asset('admin-ui/'.$path).'?v='.(@filemtime(public_path('admin-ui/'.$path)) ?: 1);
    $initials = collect(preg_split('/\s+/', trim($user?->name ?? 'A')))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('') ?: 'A';
    $pageTitle = trim($__env->yieldContent('title')) ?: 'Dashboard';
    // The menu: [label, icon, url, is it open] or a heading, or a group with children
    $is = fn (...$patterns) => collect($patterns)->contains(fn ($p) => request()->is($p));
    $menu = [
        ['label' => 'Dashboard', 'icon' => 'gauge-high', 'url' => route('admin.dashboard'), 'current' => $is('admin', 'admin/dashboard')],
        ['heading' => 'Website'],
        ['label' => 'Home Page', 'icon' => 'house', 'open' => $is('admin/sliders*', 'admin/home-counters*'), 'children' => [
            ['label' => 'Sliders', 'url' => route('admin.sliders.index'), 'current' => $is('admin/sliders*')],
            ['label' => 'Home Counters', 'url' => route('admin.home-counters.index'), 'current' => $is('admin/home-counters*')],
        ]],
        ['label' => 'Projects', 'icon' => 'building', 'open' => $is('admin/projects*', 'admin/amenities*'), 'children' => [
            ['label' => 'All Projects', 'url' => route('admin.projects.index'), 'current' => $is('admin/projects*')],
            ['label' => 'Amenities', 'url' => route('admin.amenities.index'), 'current' => $is('admin/amenities*')],
        ]],
        ['label' => 'About Us', 'icon' => 'circle-info', 'open' => $is('admin/about*', 'admin/why-us*', 'admin/team*'), 'children' => [
            ['label' => 'About Page', 'url' => route('admin.about.settings.index'), 'current' => $is('admin/about/settings*')],
            ['label' => 'About Sections', 'url' => route('admin.about.sections.index'), 'current' => $is('admin/about/sections*')],
            ['label' => 'Why Choose Us', 'url' => route('admin.why-us.edit'), 'current' => $is('admin/why-us*')],
            ['label' => 'Team Members', 'url' => route('admin.team.index'), 'current' => $is('admin/team*')],
        ]],
        ['label' => 'News', 'icon' => 'newspaper', 'open' => $is('admin/blogs*', 'admin/blog-categories*'), 'children' => [
            ['label' => 'Blog Posts', 'url' => route('admin.blogs.index'), 'current' => $is('admin/blogs*')],
            ['label' => 'Categories', 'url' => route('admin.blog-categories.index'), 'current' => $is('admin/blog-categories*')],
        ]],
        ['label' => 'Clients', 'icon' => 'handshake', 'url' => route('admin.clients.index'), 'current' => $is('admin/clients*')],
        ['label' => 'Careers', 'icon' => 'user-tie', 'url' => route('admin.careers.index'), 'current' => $is('admin/careers*')],
        ['heading' => 'Settings'],
        ['label' => 'Contact Details', 'icon' => 'address-book', 'url' => route('admin.contact.edit'), 'current' => $is('admin/contact*')],
        ['label' => 'Static Pages', 'icon' => 'file-lines', 'url' => route('admin.static-pages.index'), 'current' => $is('admin/static-pages*')],
        ['label' => 'View Website', 'icon' => 'globe', 'url' => url('/'), 'external' => true, 'current' => false],
    ];
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-sidebar="light" data-layout="vertical" data-primary="rest" data-width="fluid" data-icons="{{ $ui('icons.svg') }}" data-home="{{ route('admin.dashboard') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#035203">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $pageTitle }} | Rest Developments</title>
    <link rel="icon" href="{{ $ui('img/rest-mark.png') }}" type="image/png">
    <script>(function(){var s={};try{s=JSON.parse(localStorage.getItem('erp.ui')||'{}')}catch(e){}var h=document.documentElement;h.setAttribute('data-bs-theme',s.theme==='dark'?'dark':'light');h.setAttribute('data-layout',s.layout==='collapsed'?'collapsed':'vertical')})();</script>
    <link rel="stylesheet" href="{{ $ui('css/app.css') }}">
    {{-- Icons that the page scripts still write as Font Awesome classes --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.8/dist/sweetalert2.min.css">
    {{-- The page scripts of the panel use jQuery and SweetAlert2 --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('vendor-style')
    @yield('page-style')
    @yield('styles')
</head>
<body data-no-export>
<div class="app-wrapper">
    <aside class="app-sidebar" id="appSidebar" aria-label="Menu">
        <div class="sidebar-brand is-stacked">
            <a href="{{ route('admin.dashboard') }}" class="brand-link" title="Rest Developments">
                <span class="brand-mark"><img src="{{ $ui('img/rest-mark.png') }}" alt=""></span>
                <span class="brand-text">Rest Developments</span>
            </a>
        </div>
        <div class="sidebar-scroll" id="sidebarScroll">
            <div class="drawer-profile">
                <span class="avatar avatar-md avatar-soft-primary">{{ $initials }}</span>
                <div class="min-w-0"><strong>{{ $user?->name }}</strong><small>{{ $user?->email }}</small></div>
            </div>
            <div class="drawer-quick">
                <button type="button" data-theme-toggle><x-icon name="moon" /><span>Theme</span></button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#searchModal"><x-icon name="magnifying-glass" /><span>Search</span></button>
                <button type="submit" form="logoutForm"><x-icon name="right-from-bracket" /><span>Sign out</span></button>
            </div>
            <ul class="sidebar-menu" id="sidebarMenu">
                @foreach ($menu as $item)
                    @if (isset($item['heading']))
                        <li class="menu-heading"><span>{{ $item['heading'] }}</span></li>
                    @elseif (isset($item['children']))
                        <li class="menu-item has-sub {{ $item['open'] ? 'open' : '' }}">
                            <a class="menu-link" href="#menu-{{ $loop->index }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ $item['open'] ? 'true' : 'false' }}"><span class="menu-icon"><x-icon :name="$item['icon']" /></span><span class="menu-text">{{ $item['label'] }}</span><x-icon name="chevron-right" class="menu-arrow" /></a>
                            <ul class="menu-sub collapse {{ $item['open'] ? 'show' : '' }}" id="menu-{{ $loop->index }}">
                                @foreach ($item['children'] as $child)
                                    <li><a class="menu-sublink {{ $child['current'] ? 'active' : '' }}" href="{{ $child['url'] }}">{{ $child['label'] }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="menu-item {{ $item['current'] ? 'active' : '' }}">
                            <a class="menu-link" href="{{ $item['url'] }}" @if (! empty($item['external'])) target="_blank" rel="noopener" @endif><span class="menu-icon"><x-icon :name="$item['icon']" /></span><span class="menu-text">{{ $item['label'] }}</span>@if (! empty($item['external']))<x-icon name="arrow-up-right-from-square" class="ms-auto small text-muted" />@endif</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </aside>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="app-main">
        <header class="app-topbar" id="appTopbar">
            <button class="btn-icon btn-ghost topbar-toggle" id="sidebarToggle" type="button" aria-label="Toggle the menu"><x-icon name="bars-staggered" /></button>
            <button class="appbar-back" type="button" id="appBack" aria-label="Back"><x-icon name="chevron-left" /></button>
            <div class="appbar-title" id="appbarTitle">{{ $pageTitle }}</div>

            <button class="topbar-search" type="button" data-bs-toggle="modal" data-bs-target="#searchModal">
                <x-icon name="magnifying-glass" /><em class="visually-hidden">Search</em><span>Search pages…</span><span class="kbd-group"><kbd>Ctrl</kbd><kbd>K</kbd></span>
            </button>

            <div class="topbar-right">
                <a class="topbar-icon topbar-lang" href="{{ url('/') }}" target="_blank" rel="noopener" title="View website" aria-label="View website"><x-icon name="globe" /><span>Website</span></a>
                <button class="topbar-icon" type="button" data-theme-toggle title="Light or dark"><x-icon name="moon" /></button>
                <div class="dropdown">
                    <button class="topbar-user" data-bs-toggle="dropdown" type="button" aria-expanded="false" title="{{ $user?->name }}">
                        <span class="avatar avatar-sm avatar-soft-primary">{{ $initials }}</span><span class="visually-hidden">{{ $user?->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end user-menu">
                        <li class="user-menu-head"><span class="avatar avatar-md avatar-soft-primary">{{ $initials }}</span><div><strong>{{ $user?->name }}</strong><small>{{ $user?->email }}</small></div></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><x-icon name="gauge-high" /> Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ url('/') }}" target="_blank" rel="noopener"><x-icon name="globe" /> View Website</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><button class="dropdown-item text-danger" type="submit" form="logoutForm"><x-icon name="right-from-bracket" /> Sign out</button></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="app-content">
            <div class="content-container">
                @yield('content')
            </div>
        </main>
        <footer class="app-footer">
            <span>© {{ date('Y') }} Rest Developments</span>
            <nav><span class="text-muted">by <a href="https://fida.af" target="_blank" rel="noopener">Fida Technologies</a></span></nav>
        </footer>
    </div>
</div>

<nav class="app-tabbar" aria-label="Menu">
    <a href="{{ route('admin.dashboard') }}" class="tab-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><x-icon name="house" /><em>Home</em></a>
    <a href="{{ route('admin.projects.index') }}" class="tab-item {{ request()->is('admin/projects*') ? 'active' : '' }}"><x-icon name="building" /><em>Projects</em></a>
    <button type="button" class="tab-item" data-bs-toggle="modal" data-bs-target="#searchModal"><x-icon name="magnifying-glass" /><em>Search</em></button>
    <button type="button" class="tab-item" id="tabMenu" aria-controls="appSidebar"><x-icon name="grip" /><em>Menu</em></button>
</nav>

<div class="modal fade" id="searchModal" tabindex="-1" aria-label="Search" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable search-dialog">
        <div class="modal-content">
            <div class="search-input"><x-icon name="magnifying-glass" /><input type="text" id="searchInput" placeholder="Search pages…" autocomplete="off"><kbd>Esc</kbd></div>
            <div class="modal-body p-2" id="searchResults"></div>
        </div>
    </div>
</div>

<form id="logoutForm" method="POST" action="{{ route('logout') }}" hidden data-confirm="Sign out?" data-confirm-text="You will need your password to come back in." data-confirm-ok="Sign out" data-confirm-style="primary">@csrf</form>

@foreach (['success', 'warning', 'danger', 'info'] as $type)
    @if (session($type))<div data-toast="{{ $type }}" hidden>{{ session($type) }}</div>@endif
@endforeach
@if (session('error'))<div data-toast="danger" hidden>{{ session('error') }}</div>@endif

<script>window.mediaUrl = function (p) { if (!p) return ''; if (/^(https?:)?\/\//i.test(p)) return p; p = String(p).replace(/^\/?(public\/)?/, ''); if (!/^(storage|assets|admin-ui)\//.test(p)) p = 'storage/' + p; return {!! json_encode(rtrim(url('/'), '/').'/') !!} + p; };</script>
<script>window.ERP_TEXT = {"cancel":"Cancel","confirmOk":"Yes","confirmTitle":"Are you sure?","noResults":"No results","addNew":"New","failed":"Something went wrong. Please try again."};</script>
<script src="{{ $ui('js/app.js') }}" defer></script>
<script>
    // Bootstrap 5 style calls of the page scripts, served by the template's own modals and tabs
    window.bootstrap = window.bootstrap || {
        Modal: function (el) { this.el = typeof el === 'string' ? document.querySelector(el) : el; this.show = function () { window.ERP && ERP.showModal(this.el); }; this.hide = function () { window.ERP && ERP.hideModal(this.el); }; this.toggle = function () { this.el.classList.contains('show') ? this.hide() : this.show(); }; },
        Tab: function (el) { this.el = el; this.show = function () { this.el.click(); }; },
        Tooltip: function () {}
    };
    window.bootstrap.Modal.getInstance = window.bootstrap.Modal.getOrCreateInstance = function (el) { return new window.bootstrap.Modal(el); };
    if (window.jQuery) {
        jQuery.fn.modal = function (action) { return this.each(function () { var m = new window.bootstrap.Modal(this); (action === 'hide' ? m.hide : m.show).call(m); }); };
        jQuery.fn.tooltip = function () { return this; };
        // CSRF token for every AJAX call of the panel
        jQuery.ajaxSetup({ headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
    }
    // SweetAlert2 in the colours of the template
    if (window.Swal) {
        window.Swal = Swal.mixin({ buttonsStyling: false, customClass: { confirmButton: 'btn btn-primary mx-1', cancelButton: 'btn btn-light-secondary mx-1', denyButton: 'btn btn-danger mx-1' } });
    }
</script>
@yield('vendor-script')
@yield('page-script')
</body>
</html>
