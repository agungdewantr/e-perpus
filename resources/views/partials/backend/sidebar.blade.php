<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li>
                    <a href="{{route('dashboard')}}" style="transition: color 0.3s ease, background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#8DCBE6'; this.style.color='#fbfaff';" onmouseout="this.style.backgroundColor=''; this.style.color='';">
                        <i class="fas fa-home"style="font-size: 16px; color: inherit; transition: color 0.3s ease;"></i>
                        <span style="font-size: 14px; color: inherit; transition: color 0.3s ease;">Dashboard</span>
                    </a>
                </li>

                @foreach (tipe_menus() as $tipeMenu)
                    @php
                        $menus = user_menus()[$tipeMenu->tipe_menu];
                    @endphp
                    @if ($menus)
                        <li class="menu-title" data-key="t-management">
                        @if($tipeMenu->tipe_menu != 'other')
                            {{ucfirst($tipeMenu->tipe_menu)}}
                        @else
                            Umum
                        @endif
                        </li>
                    @endif
                    <style>
                       li a.active:hover span {
                            color: #fbfaff !important;
                        }
                        li a.active:hover i {
                            color: #fbfaff !important;
                        }
                    </style>
                    @foreach ($menus as $menu)
                    <li>
                        <a href="{{$menu->url}}" style="transition: color 0.3s ease, background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#8DCBE6'; this.style.color='#fbfaff';" onmouseout="this.style.backgroundColor=''; this.style.color='';">
                            <i class="{{$menu->icon}}" style="font-size: 16px; color: inherit; transition: color 0.3s ease;"></i>
                            <span data-key="t-{{$menu->key_nama}}" style="font-size: 14px; color: inherit; transition: color 0.3s ease;">{{$menu->nama}}</span>
                        </a>
                    </li>

                    @endforeach
                @endforeach

                {{-- <li>
                    <a href="{{route('user.index')}}">
                        <i class="fas fa-users"></i>
                        <span data-key="t-user">User</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-tags"></i>
                        <span data-key="t-dashboard">Role</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-users"></i>
                        <span data-key="t-dashboard">Menu</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-users"></i>
                        <span data-key="t-dashboard">Permission</span>
                    </a>
                </li> --}}
                {{-- <li class="menu-title" data-key="t-menu">Data Master</li> --}}

                {{-- <li>
                    <a href="{{route('role.index')}}">
                        <i class="fas fa-tags"></i>
                        <span data-key="t-dashboard">Roles</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-users"></i>
                        <span data-key="t-dashboard">Users</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-book"></i>
                        <span data-key="t-dashboard">Provinsi</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-book"></i>
                        <span data-key="t-dashboard">Kota/Kabupaten</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-book"></i>
                        <span data-key="t-dashboard">Kecamatan</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-book"></i>
                        <span data-key="t-dashboard">Kelurahan</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-newspaper"></i>
                        <span data-key="t-dashboard">Berita</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-clipboard-list"></i>
                        <span data-key="t-dashboard">Rak</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-folder-open"></i>
                        <span data-key="t-dashboard">Kategori</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                            <i class="fas fa-at"></i>
                        <span data-key="t-dashboard">Penerbit</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-swatchbook"></i>
                        <span data-key="t-dashboard">Buku</span>
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-file-audio"></i>
                        <span data-key="t-dashboard">Audio Visual</span>
                    </a>
                </li> --}}
                {{-- <li>
                    <a href="layouts-horizontal.html">
                        <i data-feather="layout"></i>
                        <span data-key="t-horizontal">Horizontal</span>
                    </a>
                </li> --}}
{{--
                <li class="menu-title mt-2" data-key="t-components">Elements</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="briefcase"></i>
                        <span data-key="t-components">Components</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ui-alerts.html" data-key="t-alerts">Alerts</a></li>
                        <li><a href="ui-buttons.html" data-key="t-buttons">Buttons</a></li>
                        <li><a href="ui-cards.html" data-key="t-cards">Cards</a></li>
                        <li><a href="ui-carousel.html" data-key="t-carousel">Carousel</a></li>
                        <li><a href="ui-dropdowns.html" data-key="t-dropdowns">Dropdowns</a></li>
                        <li><a href="ui-grid.html" data-key="t-grid">Grid</a></li>
                        <li><a href="ui-images.html" data-key="t-images">Images</a></li>
                        <li><a href="ui-modals.html" data-key="t-modals">Modals</a></li>
                        <li><a href="ui-offcanvas.html" data-key="t-offcanvas">Offcanvas</a></li>
                        <li><a href="ui-progressbars.html" data-key="t-progress-bars">Progress Bars</a></li>
                        <li><a href="ui-placeholders.html" data-key="t-progress-bars">Placeholders</a></li>
                        <li><a href="ui-tabs-accordions.html" data-key="t-tabs-accordions">Tabs & Accordions</a></li>
                        <li><a href="ui-typography.html" data-key="t-typography">Typography</a></li>
                        <li><a href="ui-toasts.html" data-key="t-typography">Toasts</a></li>
                        <li><a href="ui-video.html" data-key="t-video">Video</a></li>
                        <li><a href="ui-general.html" data-key="t-general">General</a></li>
                        <li><a href="ui-colors.html" data-key="t-colors">Colors</a></li>
                        <li><a href="ui-utilities.html" data-key="t-colors">Utilities</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);">
                        <i data-feather="box"></i>
                        <span class="badge rounded-pill badge-soft-danger  text-danger float-end">7</span>
                        <span data-key="t-forms">Forms</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="form-elements.html" data-key="t-form-elements">Basic Elements</a></li>
                        <li><a href="form-validation.html" data-key="t-form-validation">Validation</a></li>
                        <li><a href="form-advanced.html" data-key="t-form-advanced">Advanced Plugins</a></li>
                        <li><a href="form-editors.html" data-key="t-form-editors">Editors</a></li>
                        <li><a href="form-uploads.html" data-key="t-form-upload">File Upload</a></li>
                        <li><a href="form-wizard.html" data-key="t-form-wizard">Wizard</a></li>
                        <li><a href="form-mask.html" data-key="t-form-mask">Mask</a></li>
                    </ul>
                </li> --}}

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
