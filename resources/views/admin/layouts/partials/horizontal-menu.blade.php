<!-- BEGIN: Enhanced Horizontal Menu -->
<div class="header-navbar navbar-expand-sm navbar navbar-horizontal navbar-fixed navbar-dark navbar-with-shadow" role="navigation" data-menu="menu-wrapper">
    <div class="navbar-container main-menu-content" data-menu="menu-container">
        <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">

            {{-- Dashboard --}}
            <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center" href="{{ url('/admin/dashboard') }}">
                    <i class="fas fa-tachometer-alt mr-1"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Sliders --}}
            <li class="nav-item {{ request()->is('admin/sliders*') ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center" href="{{ url('/admin/sliders') }}">
                    <i class="fas fa-sliders-h mr-1"></i>
                    <span>Sliders</span>
                </a>
            </li>

            {{-- Home Sections --}}
            <li class="nav-item {{ request()->is('admin/home-counters*') ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center" href="{{ url('/admin/home-counters') }}">
                    <i class="fas fa-chart-line mr-1"></i>
                    <span>Home Counters</span>
                </a>
            </li>

            {{-- About Us --}}
            @php
                $aboutGroupActive = request()->is('admin/about*') || request()->is('admin/team-members*') || request()->is('admin/testimonials*') || request()->is('admin/why-us*');
            @endphp
            <li class="nav-item dropdown {{ $aboutGroupActive ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown">
                    <i class="fas fa-info-circle mr-1"></i>
                    <span>About Us</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right animate slideIn">
                    <li class="{{ request()->is('admin/about') || request()->is('admin/about/settings*') ? 'active' : '' }}">
                        <a class="dropdown-item d-flex align-items-center" href="{{ url('/admin/about/settings') }}">
                            <i class="fas fa-info-circle mr-2"></i> About Page
                        </a>
                    </li>

                    <li class="{{ request()->is('admin/about/sections*') ? 'active' : '' }}">
                        <a class="dropdown-item d-flex align-items-center" href="{{ url('/admin/about/sections') }}">
                            <i class="fas fa-layer-group mr-2"></i> About Sections
                        </a>
                    </li>

                    <li class="{{ request()->is('admin/why-us*') ? 'active' : '' }}">
                        <a class="dropdown-item d-flex align-items-center" href="{{ url('/admin/why-us') }}">
                            <i class="fas fa-award mr-2"></i> Why Choose Us
                        </a>
                    </li>

                    <li class="{{ request()->is('admin/team*') ? 'active' : '' }}">
                        <a class="dropdown-item d-flex align-items-center" href="{{ url('/admin/team') }}">
                            <i class="fas fa-users mr-2"></i> Team Members
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Projects --}}
            @php
                $projectsActive = request()->is('admin/projects') || request()->is('admin/projects/*') || request()->routeIs('admin.projects.units.*');
            @endphp
            <li class="nav-item {{ $projectsActive ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center" href="{{ url('/admin/projects') }}">
                    <i class="fas fa-building mr-1"></i>
                    <span>Projects</span>
                </a>
            </li>

            {{-- Amenities --}}
            @php
                $amenitiesActive = request()->is('admin/amenities') || request()->is('admin/amenities/*');
            @endphp
            <li class="nav-item {{ $amenitiesActive ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center" href="{{ url('/admin/amenities') }}">
                    <i class="fas fa-swimming-pool mr-1"></i>
                    <span>Amenities</span>
                </a>
            </li>

            {{-- Blogs --}}
            @php
                $blogGroupsActive = request()->is('admin/blogs') || request()->is('admin/blogs/*') || request()->is('admin/blog-categories*');
            @endphp
            <li class="nav-item dropdown {{ $blogGroupsActive ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown">
                    <i class="fas fa-blog mr-1"></i>
                    <span>Blogs</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right animate slideIn">
                    <li class="{{ request()->is('admin/blogs*') ? 'active' : '' }}">
                        <a class="dropdown-item d-flex align-items-center" href="{{ url('/admin/blogs') }}">
                            <i class="fas fa-newspaper mr-2"></i> Blog Posts
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/blog-categories*') ? 'active' : '' }}">
                        <a class="dropdown-item d-flex align-items-center" href="{{ url('/admin/blog-categories') }}">
                            <i class="fas fa-tags mr-2"></i> Blog Categories
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Clients --}}
            @php
                $clientsActive = request()->is('admin/clients') || request()->is('admin/clients/*');
            @endphp
            <li class="nav-item {{ $clientsActive ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center" href="{{ url('/admin/clients') }}">
                    <i class="fas fa-handshake mr-1"></i>
                    <span>Clients</span>
                </a>
            </li>

            {{-- Contact --}}
            @php
                $contactPageActive = request()->is('admin/contact');
            @endphp
            <li class="nav-item {{ $contactPageActive ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center" href="{{ url('/admin/contact') }}">
                    <i class="fas fa-envelope mr-1"></i>
                    <span>Contact</span>
                </a>
            </li>


            {{-- Vacancies --}}
            @php
                $vacanciesActive = request()->is('admin/careers') || request()->is('admin/careers/*');
            @endphp
            <li class="nav-item {{ $vacanciesActive ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center" href="{{ url('/admin/careers') }}">
                    <i class="fas fa-user-tie mr-1"></i>
                    <span>Careers</span>
                </a>
            </li>


            {{-- Static Pages --}}
            @php
                $staticPagesActive = request()->is('admin/static-pages') || request()->is('admin/static-pages/*');
            @endphp
            <li class="nav-item {{ $staticPagesActive ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center" href="{{ request()->is('admin/static-pages') ? '#' : url('/admin/static-pages') }}">
                    <i class="fas fa-file mr-1"></i>
                    <span>Static Pages</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- END: Enhanced Horizontal Menu -->
