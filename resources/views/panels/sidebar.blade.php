<aside
  class="{{$configData['sidenavMain']}} @if(!empty($configData['activeMenuType'])) {{$configData['activeMenuType']}} @else {{$configData['activeMenuTypeClass']}}@endif @if(($configData['isMenuDark']) === true) {{'sidenav-dark'}} @elseif(($configData['isMenuDark']) === false){{'sidenav-light'}}  @else {{$configData['sidenavMainColor']}}@endif">
  <div class="brand-sidebar">
    <h1 class="logo-wrapper">
      <a class="brand-logo darken-1}}" href="{{asset('/')}}">
        @if(!empty($configData['mainLayoutType']) && isset($configData['mainLayoutType']))
          @if($configData['mainLayoutType']=== 'vertical-modern-menu')
          @php
           $org = DB::Table('organisations')->select('image')->where('organisation_id', '=', auth()->user()->organisation_id)->get(); //echo $org['0']->image;
          @endphp
          @if(@$org['0']->image)
            @if(auth()->user()->role != 'global_administrator')
              <img class="hide-on-med-and-down" src="{{$org['0']->image}}" alt="materialize logo" style="height: auto;width: 100%;" />
              <img class="show-on-medium-and-down hide-on-med-and-up" src="{{$org['0']->image}}"
                alt="materialize logo" />
            @else
              <img class="hide-on-med-and-down" src="{{asset($configData['largeScreenLogo'])}}" alt="materialize logo" style="height: auto;width: 100%;" />
              <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['smallScreenLogo'])}}"
                alt="materialize logo" />
            @endif
          @else
          <img class="hide-on-med-and-down" src="{{asset($configData['largeScreenLogo'])}}" alt="materialize logo" style="height: auto;width: 100%;" />
          <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['smallScreenLogo'])}}"
            alt="materialize logo" />
          @endif

          @elseif($configData['mainLayoutType']=== 'vertical-menu-nav-dark')
          <img src="{{asset($configData['smallScreenLogo'])}}" alt="materialize logo" />

          @elseif($configData['mainLayoutType']=== 'vertical-gradient-menu')
          <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['largeScreenLogo'])}}"
            alt="materialize logo" />
          <img class="hide-on-med-and-down" src="{{asset($configData['smallScreenLogo'])}}" alt="materialize logo" />

          @elseif($configData['mainLayoutType']=== 'vertical-dark-menu')
          <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['largeScreenLogo'])}}"
            alt="materialize logo" />
          <img class="hide-on-med-and-down" src="{{asset($configData['smallScreenLogo'])}}" alt="materialize logo" />
          @endif
        @endif
        <span class="logo-text hide-on-med-and-down" style="display: none;">
          @if(!empty ($configData['templateTitle']) && isset($configData['templateTitle']))
          {{$configData['templateTitle']}}
          @else
          Materialize
          @endif
        </span>
      </a>
      <!-- <a class="navbar-toggler" href="javascript:void(0)"><i class="material-icons">radio_button_checked</i></a></h1> -->
  </div>
  <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out"
    data-menu="menu-navigation" data-collapsible="menu-accordion">
    {{-- Foreach menu item starts --}}
    @if(!empty($menuData[0]) && isset($menuData[0]))
      @foreach ($menuData[0]->menu as $menu)
        @if(isset($menu->navheader))
          @if(auth()->user()->role == 'global_administrator')

            @if($menu->navheader == 'Administration')

            <li class="navigation-header">
              <a class="navigation-header-text">{{ $menu->navheader }}</a>
              <i class="navigation-header-icon material-icons">{{$menu->icon }}</i>
            </li>
            @endif
          @else
            @if($menu->navheader != 'Administration')

            <li class="navigation-header">
              <a class="navigation-header-text">{{ $menu->navheader }}</a>
              <i class="navigation-header-icon material-icons">{{$menu->icon }}</i>
            </li>
            @endif
         <!--  <li class="navigation-header">
            <a class="navigation-header-text">{{ $menu->navheader }}</a>
            <i class="navigation-header-icon material-icons">{{$menu->icon }}</i>
          </li> -->
          @endif 
        @else
        @php
          $custom_classes="";
          if(isset($menu->class))
          {
          $custom_classes = $menu->class;
          }
          
        @endphp
      @if(auth()->user()->role == 'global_administrator')
        @if($menu->name == 'Dashboard' || $menu->name == 'Organisations' || $menu->name == 'Org Admins' || $menu->name == 'Settings' || $menu->name == 'How to Play' || $menu->name == 'FAQs' || $menu->name == 'Challenges' || $menu->name == 'Prize Category')

          <li class="bold {{(request()->is($menu->url.'*')) ? 'active' : '' }}">
          <a class="{{$custom_classes}} {{ (request()->is($menu->url.'*')) ? 'active '.$configData['activeMenuColor'] : ''}}"
            @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
            href="@if(($menu->url)==='javascript:void(0)'){{$menu->url}} @else{{url($menu->url)}} @endif"
            {{isset($menu->newTab) ? 'target="_blank"':''}}>
            <i class="material-icons">{{$menu->icon}}</i>
            <span class="menu-title">{{ __('locale.'.$menu->name)}}</span>
            @if(isset($menu->tag))
            <span class="{{$menu->tagcustom}}">{{$menu->tag}}</span>
            @endif
          </a>
            @if(isset($menu->submenu))
            @include('panels.submenu', ['menu' => $menu->submenu])
            @endif
          </li>
        @endif
      @elseif(auth()->user()->role == 'organisation_administrator')
        @if($menu->name == 'Dashboard' || $menu->name == 'Prizes' || $menu->name == 'Challenges' || $menu->name == 'Competitions' || $menu->name == 'Reports' || $menu->name == 'Users')

          <li class="bold {{(request()->is($menu->url.'*')) ? 'active' : '' }}">
            <a class="{{$custom_classes}} {{ (request()->is($menu->url.'*')) ? 'active '.$configData['activeMenuColor'] : ''}}"
              @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
              href="@if(($menu->url)==='javascript:void(0)'){{$menu->url}} @else{{url($menu->url)}} @endif"
              {{isset($menu->newTab) ? 'target="_blank"':''}}>
              <i class="material-icons">{{$menu->icon}}</i>
              <span class="menu-title">{{ __('locale.'.$menu->name)}}</span>
              @if(isset($menu->tag))
              <span class="{{$menu->tagcustom}}">{{$menu->tag}}</span>
              @endif
            </a>
              @if(isset($menu->submenu))
              @include('panels.submenu', ['menu' => $menu->submenu])
              @endif
          </li>
        @endif
      @elseif(auth()->user()->role == 'competition_administrator')
        @if($menu->name == 'Dashboard' || $menu->name == 'Prizes' || $menu->name == 'Competitions')

          <li class="bold {{(request()->is($menu->url.'*')) ? 'active' : '' }}">
            <a class="{{$custom_classes}} {{ (request()->is($menu->url.'*')) ? 'active '.$configData['activeMenuColor'] : ''}}"
              @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
              href="@if(($menu->url)==='javascript:void(0)'){{$menu->url}} @else{{url($menu->url)}} @endif"
              {{isset($menu->newTab) ? 'target="_blank"':''}}>
              <i class="material-icons">{{$menu->icon}}</i>
              <span class="menu-title">{{ __('locale.'.$menu->name)}}</span>
              @if(isset($menu->tag))
              <span class="{{$menu->tagcustom}}">{{$menu->tag}}</span>
              @endif
            </a>
              @if(isset($menu->submenu))
              @include('panels.submenu', ['menu' => $menu->submenu])
              @endif
          </li>
        @endif
      @elseif(auth()->user()->role == 'user_administrator')
        @if($menu->name == 'Dashboard' || $menu->name == 'Users')

          <li class="bold {{(request()->is($menu->url.'*')) ? 'active' : '' }}">
            <a class="{{$custom_classes}} {{ (request()->is($menu->url.'*')) ? 'active '.$configData['activeMenuColor'] : ''}}"
              @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
              href="@if(($menu->url)==='javascript:void(0)'){{$menu->url}} @else{{url($menu->url)}} @endif"
              {{isset($menu->newTab) ? 'target="_blank"':''}}>
              <i class="material-icons">{{$menu->icon}}</i>
              <span class="menu-title">{{ __('locale.'.$menu->name)}}</span>
              @if(isset($menu->tag))
              <span class="{{$menu->tagcustom}}">{{$menu->tag}}</span>
              @endif
            </a>
              @if(isset($menu->submenu))
              @include('panels.submenu', ['menu' => $menu->submenu])
              @endif
          </li>
        @endif
      @elseif(auth()->user()->role == 'prize_administrator')
        @if($menu->name == 'Dashboard' || $menu->name == 'Prizes' || $menu->name == 'Challenges')

          <li class="bold {{(request()->is($menu->url.'*')) ? 'active' : '' }}">
            <a class="{{$custom_classes}} {{ (request()->is($menu->url.'*')) ? 'active '.$configData['activeMenuColor'] : ''}}"
              @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
              href="@if(($menu->url)==='javascript:void(0)'){{$menu->url}} @else{{url($menu->url)}} @endif"
              {{isset($menu->newTab) ? 'target="_blank"':''}}>
              <i class="material-icons">{{$menu->icon}}</i>
              <span class="menu-title">{{ __('locale.'.$menu->name)}}</span>
              @if(isset($menu->tag))
              <span class="{{$menu->tagcustom}}">{{$menu->tag}}</span>
              @endif
            </a>
              @if(isset($menu->submenu))
              @include('panels.submenu', ['menu' => $menu->submenu])
              @endif
          </li>
        @endif
      @elseif(auth()->user()->role == 'competition_administrator,user_administrator,prize_administrator')
        @if($menu->name == 'Dashboard' || $menu->name == 'Prizes' || $menu->name == 'Challenges' || $menu->name == 'Competitions' || $menu->name == 'Users')

          <li class="bold {{(request()->is($menu->url.'*')) ? 'active' : '' }}">
            <a class="{{$custom_classes}} {{ (request()->is($menu->url.'*')) ? 'active '.$configData['activeMenuColor'] : ''}}"
              @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
              href="@if(($menu->url)==='javascript:void(0)'){{$menu->url}} @else{{url($menu->url)}} @endif"
              {{isset($menu->newTab) ? 'target="_blank"':''}}>
              <i class="material-icons">{{$menu->icon}}</i>
              <span class="menu-title">{{ __('locale.'.$menu->name)}}</span>
              @if(isset($menu->tag))
              <span class="{{$menu->tagcustom}}">{{$menu->tag}}</span>
              @endif
            </a>
              @if(isset($menu->submenu))
              @include('panels.submenu', ['menu' => $menu->submenu])
              @endif
          </li>
        @endif
      @elseif(auth()->user()->role == 'competition_administrator,user_administrator')
        @if($menu->name == 'Dashboard' || $menu->name == 'Prizes' || $menu->name == 'Competitions' || $menu->name == 'Users')

          <li class="bold {{(request()->is($menu->url.'*')) ? 'active' : '' }}">
            <a class="{{$custom_classes}} {{ (request()->is($menu->url.'*')) ? 'active '.$configData['activeMenuColor'] : ''}}"
              @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
              href="@if(($menu->url)==='javascript:void(0)'){{$menu->url}} @else{{url($menu->url)}} @endif"
              {{isset($menu->newTab) ? 'target="_blank"':''}}>
              <i class="material-icons">{{$menu->icon}}</i>
              <span class="menu-title">{{ __('locale.'.$menu->name)}}</span>
              @if(isset($menu->tag))
              <span class="{{$menu->tagcustom}}">{{$menu->tag}}</span>
              @endif
            </a>
              @if(isset($menu->submenu))
              @include('panels.submenu', ['menu' => $menu->submenu])
              @endif
          </li>
        @endif
      @elseif(auth()->user()->role == 'competition_administrator,prize_administrator')
        @if($menu->name == 'Dashboard' || $menu->name == 'Prizes' || $menu->name == 'Challenges' || $menu->name == 'Competitions')

          <li class="bold {{(request()->is($menu->url.'*')) ? 'active' : '' }}">
            <a class="{{$custom_classes}} {{ (request()->is($menu->url.'*')) ? 'active '.$configData['activeMenuColor'] : ''}}"
              @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
              href="@if(($menu->url)==='javascript:void(0)'){{$menu->url}} @else{{url($menu->url)}} @endif"
              {{isset($menu->newTab) ? 'target="_blank"':''}}>
              <i class="material-icons">{{$menu->icon}}</i>
              <span class="menu-title">{{ __('locale.'.$menu->name)}}</span>
              @if(isset($menu->tag))
              <span class="{{$menu->tagcustom}}">{{$menu->tag}}</span>
              @endif
            </a>
              @if(isset($menu->submenu))
              @include('panels.submenu', ['menu' => $menu->submenu])
              @endif
          </li>
        @endif
      @elseif(auth()->user()->role == 'user_administrator,prize_administrator')
        @if($menu->name == 'Dashboard' || $menu->name == 'Prizes' || $menu->name == 'Challenges' || $menu->name == 'Users')

          <li class="bold {{(request()->is($menu->url.'*')) ? 'active' : '' }}">
            <a class="{{$custom_classes}} {{ (request()->is($menu->url.'*')) ? 'active '.$configData['activeMenuColor'] : ''}}"
              @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
              href="@if(($menu->url)==='javascript:void(0)'){{$menu->url}} @else{{url($menu->url)}} @endif"
              {{isset($menu->newTab) ? 'target="_blank"':''}}>
              <i class="material-icons">{{$menu->icon}}</i>
              <span class="menu-title">{{ __('locale.'.$menu->name)}}</span>
              @if(isset($menu->tag))
              <span class="{{$menu->tagcustom}}">{{$menu->tag}}</span>
              @endif
            </a>
              @if(isset($menu->submenu))
              @include('panels.submenu', ['menu' => $menu->submenu])
              @endif
          </li>
        @endif
      @else
      <li class="bold {{(request()->is($menu->url.'*')) ? 'active' : '' }}">
        <a class="{{$custom_classes}} {{ (request()->is($menu->url.'*')) ? 'active '.$configData['activeMenuColor'] : ''}}"
          @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
          href="@if(($menu->url)==='javascript:void(0)'){{$menu->url}} @else{{url($menu->url)}} @endif"
          {{isset($menu->newTab) ? 'target="_blank"':''}}>
          <i class="material-icons">{{$menu->icon}}</i>
          <span class="menu-title">{{ __('locale.'.$menu->name)}}</span>
          @if(isset($menu->tag))
          <span class="{{$menu->tagcustom}}">{{$menu->tag}}</span>
          @endif
        </a>
          @if(isset($menu->submenu))
          @include('panels.submenu', ['menu' => $menu->submenu])
          @endif
        </li>
        @endif
        @endif
      @endforeach
    @endif
  </ul>
  <div class="navigation-background"></div>
  <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only"
    href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>