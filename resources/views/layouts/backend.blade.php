<!doctype html>
<html lang="en">

    <head>
        @include("partials.backend.titlemeta")

        <!-- plugin css -->
        {{-- <link href="{{ asset('/') }}admin_assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" /> --}}

        @include("partials.backend.headcss")
        @yield("scriptatas")
    </head>

    <body>

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            @include("partials.backend.topbar")

            @include("partials.backend.sidebar")
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">


                        @yield('content')
                        @include('partials.modals')

                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
                @include("partials.backend.footer")

            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        {{-- rightsidebar --}}
            <!-- Right Sidebar -->
                <div class="right-bar">
                    <div data-simplebar class="h-100">
                        <div class="rightbar-title d-flex align-items-center p-3">

                            <h5 class="m-0 me-2">Theme Customizer</h5>

                            <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                                <i class="mdi mdi-close noti-icon"></i>
                            </a>
                        </div>

                        <!-- Settings -->
                        <hr class="m-0" />

                        <div class="p-4">
                            <h6 class="mb-3">Layout</h6>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="layout"
                                    id="layout-vertical" value="vertical">
                                <label class="form-check-label" for="layout-vertical">Vertical</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="layout"
                                    id="layout-horizontal" value="horizontal">
                                <label class="form-check-label" for="layout-horizontal">Horizontal</label>
                            </div>

                            <h6 class="mt-4 mb-3 pt-2">Layout Mode</h6>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="layout-mode"
                                    id="layout-mode-light" value="light">
                                <label class="form-check-label" for="layout-mode-light">Light</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="layout-mode"
                                    id="layout-mode-dark" value="dark">
                                <label class="form-check-label" for="layout-mode-dark">Dark</label>
                            </div>

                            <h6 class="mt-4 mb-3 pt-2">Layout Width</h6>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="layout-width"
                                    id="layout-width-fuild" value="fuild" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
                                <label class="form-check-label" for="layout-width-fuild">Fluid</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="layout-width"
                                    id="layout-width-boxed" value="boxed" onchange="document.body.setAttribute('data-layout-size', 'boxed')">
                                <label class="form-check-label" for="layout-width-boxed">Boxed</label>
                            </div>

                            <h6 class="mt-4 mb-3 pt-2">Layout Position</h6>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="layout-position"
                                    id="layout-position-fixed" value="fixed" onchange="document.body.setAttribute('data-layout-scrollable', 'false')">
                                <label class="form-check-label" for="layout-position-fixed">Fixed</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="layout-position"
                                    id="layout-position-scrollable" value="scrollable" onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                                <label class="form-check-label" for="layout-position-scrollable">Scrollable</label>
                            </div>

                            <h6 class="mt-4 mb-3 pt-2">Topbar Color</h6>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="topbar-color"
                                    id="topbar-color-light" value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                                <label class="form-check-label" for="topbar-color-light">Light</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="topbar-color"
                                    id="topbar-color-dark" value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
                                <label class="form-check-label" for="topbar-color-dark">Dark</label>
                            </div>

                            <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Size</h6>

                            <div class="form-check sidebar-setting">
                                <input class="form-check-input" type="radio" name="sidebar-size"
                                    id="sidebar-size-default" value="default" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
                                <label class="form-check-label" for="sidebar-size-default">Default</label>
                            </div>
                            <div class="form-check sidebar-setting">
                                <input class="form-check-input" type="radio" name="sidebar-size"
                                    id="sidebar-size-compact" value="compact" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
                                <label class="form-check-label" for="sidebar-size-compact">Compact</label>
                            </div>
                            <div class="form-check sidebar-setting">
                                <input class="form-check-input" type="radio" name="sidebar-size"
                                    id="sidebar-size-small" value="small" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
                                <label class="form-check-label" for="sidebar-size-small">Small (Icon View)</label>
                            </div>

                            <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Color</h6>

                            <div class="form-check sidebar-setting">
                                <input class="form-check-input" type="radio" name="sidebar-color"
                                    id="sidebar-color-light" value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
                                <label class="form-check-label" for="sidebar-color-light">Light</label>
                            </div>
                            <div class="form-check sidebar-setting">
                                <input class="form-check-input" type="radio" name="sidebar-color"
                                    id="sidebar-color-dark" value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
                                <label class="form-check-label" for="sidebar-color-dark">Dark</label>
                            </div>
                            <div class="form-check sidebar-setting">
                                <input class="form-check-input" type="radio" name="sidebar-color"
                                    id="sidebar-color-brand" value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
                                <label class="form-check-label" for="sidebar-color-brand">Brand</label>
                            </div>

                            <h6 class="mt-4 mb-3 pt-2">Direction</h6>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="layout-direction"
                                    id="layout-direction-ltr" value="ltr">
                                <label class="form-check-label" for="layout-direction-ltr">LTR</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="layout-direction"
                                    id="layout-direction-rtl" value="rtl">
                                <label class="form-check-label" for="layout-direction-rtl">RTL</label>
                            </div>

                        </div>

                    </div> <!-- end slimscroll-menu-->
                </div>
            <!-- /Right-bar -->

            <!-- Right bar overlay-->
            <div class="rightbar-overlay"></div>
        {{-- rightsidebar --}}

        @include("partials.backend.vendorscripts")
        @yield('vendorscripts')
        <!-- apexcharts -->
        <script src="{{ asset('/') }}admin_assets/libs/apexcharts/apexcharts.min.js"></script>

        <!-- Plugins js-->
        <script src="{{ asset('/') }}admin_assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="{{ asset('/') }}admin_assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

         <!-- Required datatable js -->
         <script src="{{ asset('/') }}admin_assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
         <script src="{{ asset('/') }}admin_assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
         <script src="{{ asset('/') }}admin_assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
         <script src="{{ asset('/') }}admin_assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
         <script src="{{ asset('/') }}admin_assets/libs/jszip/jszip.min.js"></script>
         <script src="{{ asset('/') }}admin_assets/libs/pdfmake/build/pdfmake.min.js"></script>
         <script src="{{ asset('/') }}admin_assets/libs/pdfmake/build/vfs_fonts.js"></script>
         <script src="{{ asset('/') }}admin_assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
         <script src="{{ asset('/') }}admin_assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
         <script src="{{ asset('/') }}admin_assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
        <!-- Responsive examples -->
         <script src="{{ asset('/') }}admin_assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
         <script src="{{ asset('/') }}admin_assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

        <!-- Datatable init js -->
         {{-- <script src="{{ asset('/') }}admin_assets/js/pages/datatables.init.js"></script> --}}
        <!-- dashboard init -->
         {{-- <script src="{{ asset('/') }}admin_assets/js/pages/dashboard.init.js"></script> --}}

        {{-- <script src="{{ asset('/') }}admin_assets/js/pages/modal.init.js"></script> --}}
        @yield('scriptbawah')
        @stack('scripts')
         <script src="{{ asset('/') }}admin_assets/js/app.js"></script>

        @if ($message = Session::get('success'))
            <script>
                $(document).ready(function() {
                    toastr['success']('', '{!! $message !!}', {
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true
                    });
                });
            </script>
        @endif
        <script>
            $(document).ready(function() {
                // jika save theme dark yang tersimpan
                if (localStorage.getItem("theme") === "dark") {
                    document.body.setAttribute("data-bs-theme", "dark");
                    document.body.setAttribute("data-topbar", "dark");
                    document.body.setAttribute("data-sidebar", "dark");
                }
            });
            //OPEN GANTI THEME
                $("#btn-change-theme").on("click", function (event) {
                    if (document.body.getAttribute("data-bs-theme") === "dark") {
                        // Switch them light
                        document.body.setAttribute("data-bs-theme", "light");
                        document.body.setAttribute("data-topbar", "light");
                        document.body.setAttribute("data-sidebar", "light");

                        // simpan ke localstorage
                        localStorage.setItem("theme", "light");

                    } else {
                        // Switch to dark mode
                        document.body.setAttribute("data-bs-theme", "dark");
                        document.body.setAttribute("data-topbar", "dark");
                        document.body.setAttribute("data-sidebar", "dark");

                        // simpan ke localstorage
                        localStorage.setItem("theme", "dark");
                    }
                });
            //CLOSE GANTI THEME
        </script>
    </body>

</html>
