<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- ! Hide app brand if navbar-full -->
  <div class="app-brand demo">
    <a href="{{url('/')}}" class="app-brand-link" style="width:100%">
      <img src="{{ asset('img/Manggala_Agni.png') }}" alt="Logo" width="50" class="m-auto">
      <span class="app-brand-text menu-text fw-bold ms-2 text-uppercase">Dashboard<br>Pelaporan</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-autod-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)

    {{-- adding active and open class if child is active --}}

    {{-- menu headers --}}
    @if (isset($menu->menuHeader))
      @if(Auth::user()->hasAnyPermission($menu->permissions))
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">{{ $menu->menuHeader }}</span>
        </li>
      @endif
    @else

    {{-- active menu method --}}
    @php
    $activeClass = null;
    $currentRouteName = Request::segment(1);

    if ($currentRouteName === $menu->slug) {
      $activeClass = 'active open';
    }
    elseif (isset($menu->submenu)) {
      if (gettype($menu->slug) === 'array') {
        foreach($menu->slug as $slug){
          if (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0) {
            $activeClass = 'active open';
          }
        }
      }
      else{
        if (str_contains($currentRouteName,$menu->slug) and strpos($currentRouteName,$menu->slug) === 0) {
          $activeClass = 'active open';
        }
      }
    }
    @endphp

    {{-- main menu --}}
      @if(Auth::user()->hasAnyPermission($menu->permissions))
        <li class="menu-item {{$activeClass}}">
          <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
            @isset($menu->icon)
            <i class="{{ $menu->icon }}"></i>
            @endisset
            <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
          </a>

          {{-- submenu --}}
          @isset($menu->submenu)
          @include('layouts.sections.menu.submenu',['menu' => $menu->submenu])
          @endisset
        </li>
      @endif
    @endif
    @endforeach
  </ul>

</aside>
