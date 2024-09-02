<!DOCTYPE html>
<html>

<head>
    <!-- Title -->
    <title> MODUL BERGAMBAR </title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('/') }}web_assets/images/logo-dark.png" type="image/x-icon" />

    <!-- Bootstrap css -->
    <link href="{{ asset('/') }}web_assets/css/style.css" rel="stylesheet" />

    <!-- Icons css -->
    <link href="assets/css/icons.css" rel="stylesheet">

    <!-- Sidemenu css -->
    <link rel="stylesheet" href="assets/css/sidemenu.css">


    <!-- style css -->
    <link href="{{ asset('/') }}web_assets/css/style.css" rel="stylesheet">

    <!--- Color css-->
    <link id="theme" href="assets/css/colors/color.css" rel="stylesheet">

    <!---Skinmodes css-->
    <link href="assets/css/skin-modes.css" rel="stylesheet" />

    <style type="text/css">
        .container_buku {
            height: 80vh;
            width: 800px;
            margin: 0px auto;
        }
    </style>
</head>

<body class="main-body light-theme horizontal-color header-light">

    <div class="main-content horizontal-content">

        <!-- container opened -->
        <div class="container" style="max-width:900px">

            <!-- breadcrumb -->

            <div style="margin-top: 10px" class="row align-items-center">
                <div class="col-6 px-5">
                    <img height="60px" src="{{ asset('/') }}web_assets/images/Logo-toli.png" />
                </div>
                <div class="col-6 px-5">
                    <img src="{{ asset('/') }}web_assets/images/logo-dark.png" />
                </div>
            </div>
            <!-- /breadcrumb -->

            <div class="row row-sm">
                <div class="col-xl-12">


                    <div class="container_buku" id="container_buku">

                    </div>

                </div>
            </div>
            <!-- row close -->
        </div>
        <!-- /Container -->
    </div>
    <!-- /main-content -->


    <!-- Footer opened -->
    <div class="main-footer">
        <div class="container-fluid">
            <p class="text-center">Copyright © 2023 PERPUSTAKAAN KABUPATEN TOLITOLI</p>
        </div>
    </div>

    <script src="{{ asset('/') }}web_assets/js/jquery.min.js"></script>
    <script src="{{ asset('/') }}web_assets/js/html2canvas.min.js"></script>
    <script src="{{ asset('/') }}web_assets/js/three.min.js"></script>
    <script src="{{ asset('/') }}web_assets/js/pdf.min.js"></script>

    <script src="{{ asset('/') }}web_assets/js/3dflipbook.js"></script>
    <script type="text/javascript">
        // // Sample 0 {
        // $('#container').FlipBook({
        //   pdf: 'books/pdf/FoxitPdfSdk.pdf',
        //   template: {
        //     html: 'templates/default-book-view.html',
        //     styles: [
        //       'css/short-black-book-view.css'
        //     ],
        //     links: [
        //       {
        //         rel: 'stylesheet',
        //         href: 'css/font-awesome.min.css'
        //       }
        //     ],
        //     script: 'js/default-book-view.js'
        //   }
        // });
        // // }

        // Sample 1 {
        function theKingIsBlackPageCallback(n) {
            return {
                type: 'image',
                src: 'books/image/theKingIsBlack/' + (n + 1) + '.jpg',
                interactive: false
            };
        }

        $('#container_buku').FlipBook({
            pdf: '{{ asset('storage/' . $buku->digital->file_path) }}',
            template: {
                html: '{{ asset('/') }}web_assets/html/default-book-view.html',
                styles: [
                    '{{ asset('/') }}web_assets/css/short-white-book-view.css'
                ],
                links: [{
                    rel: 'stylesheet',
                    href: '{{ asset('/') }}web_assets/icons/fontawesome/css/all.min.css'
                }],
                script: '{{ asset('/') }}web_assets/js/default-book-view.js',
                sounds: {
                    startFlip: '{{ asset('/') }}web_assets/sounds/start-flip.mp3',
                    endFlip: '{{ asset('/') }}web_assets/sounds/end-flip.mp3'
                }
            },
            controlsProps: {
                actions: {
                    cmdPrint: {
                        enabled: false,
                        enabledInNarrow: false
                    }
                }
            }
        });
        // }

        // // Sample 2 {
        // function preview(n) {
        //   return {
        //     type: 'html',
        //     src: 'books/html/preview/'+(n%3+1)+'.html',
        //     interactive: true
        //   };
        // }
        //
        // $('#container').FlipBook({
        //   pageCallback: preview,
        //   pages: 20,
        //   propertiesCallback: function(props) {
        //     props.sheet.color = 0x008080;
        //     props.cover.padding = 0.002;
        //     return props;
        //   },
        //   template: {
        //     html: 'templates/default-book-view.html',
        //     styles: [
        //       'css/black-book-view.css'
        //     ],
        //     links: [
        //       {
        //         rel: 'stylesheet',
        //         href: 'css/font-awesome.min.css'
        //       }
        //     ],
        //     script: 'js/default-book-view.js'
        //   }
        // });
        // // }
    </script>

</body>

</html>
