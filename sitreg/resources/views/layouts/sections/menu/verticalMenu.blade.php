@php
    $configData = Helper::appClasses();
@endphp

<style>
    .bg-menu-theme .menu-inner>.menu-item.active>.menu-link {
        color: #00438F !important;
        background-color: #F5F5F9 !important;
    }

    .bg-menu-theme .menu-inner>.menu-item.active:before {
        background: #00438F !important;
    }

    .app-brand .layout-menu-toggle {
        background-color: #00438F !important;
    }

    #template-customizer .template-customizer-open-btn {
        background: #00438F !important;
        display: none !important;
    }

    .nav-pills .nav-link.active,
    .nav-pills .nav-link.active:hover,
    .nav-pills .nav-link.active:focus {
        background-color: #00438F !important;
    }

    .btn-primary {
        background-color: #00438F !important;
        border-color: #00438F !important;
    }

    .btn-primary:hover {
        background-color: #00438F !important;
        border-color: #00438F !important;
    }

    .bg-menu-theme .menu-sub>.menu-item.active>.menu-link:not(.menu-toggle):before {
        background-color: #00438F !important;
    }

    .page-item.active .page-link,
    .page-item.active .page-link:hover,
    .page-item.active .page-link:focus,
    .pagination li.active>a:not(.page-link),
    .pagination li.active>a:not(.page-link):hover,
    .pagination li.active>a:not(.page-link):focus {
        background-color: #00438F !important;
        border-color: #00438F !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #00438F !important;
    }

    .switch-primary.switch .switch-input:checked~.switch-toggle-slider {
        background: #00438F !important;
        background-color: #00438F !important;
    }

    .text-body[href]:hover {
        color: #00438F !important;
    }

    :root {
        --bs-link-color: #00438F !important;
    }
</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
   @if (!isset($navbarFull))
        <div class="app-brand demo" style="padding-left: 1.3rem;">
            <a href="{{ url('/dashboard') }}" class="app-brand-link">
                <img src="{{ asset('assets/images/sitreg.png') }}" alt="Nylitical" class="app-brand-logo demo"
                    style="width: 4rem; height: 3rem; object-fit: cover; background-color: white; padding: 5px; border-radius: 7px; margin-left: -5px;">
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
        </div>
    @endif

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuData[0]->menu as $menu)
            {{-- adding active and open class if child is active --}}

            {{-- menu headers --}}
            @if (isset($menu->menuHeader))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">{{ $menu->menuHeader }}</span>
                </li>
            @else
                {{-- active menu method --}}
                @php
                    $activeClass = null;
                    $currentRouteName = Route::currentRouteName();

                    if ($currentRouteName === $menu->slug) {
                        $activeClass = 'active';
                    } elseif (isset($menu->submenu)) {
                        if (gettype($menu->slug) === 'array') {
                            foreach ($menu->slug as $slug) {
                                if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
                                    $activeClass = 'active open';
                                }
                            }
                        } else {
                            if (str_contains($currentRouteName, $menu->slug) and strpos($currentRouteName, $menu->slug) === 0) {
                                $activeClass = 'active open';
                            }
                        }
                    }
                @endphp

                {{-- main menu --}}
                <li class="menu-item {{ $activeClass }}">
                    <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                        class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                        @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                        @isset($menu->icon)
                            <i class="{{ $menu->icon }}"></i>
                        @endisset
                        <div class="text-truncate">{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                    </a>

                    {{-- submenu --}}
                    @isset($menu->submenu)
                        @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
                    @endisset
                </li>
            @endif
        @endforeach
    </ul>

</aside>
