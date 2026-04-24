<!--begin::Head-->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? '' }} | Digital TalentHub</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">


    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/jqvmap/jqvmap.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/dist/css/adminlte.min.css') }}">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/daterangepicker/daterangepicker.css') }}">

    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/summernote/summernote-bs4.min.css') }}">

    <!-- ================== DATATABLES CSS ================== -->

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">

    <!-- DataTables Responsive -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <!-- DataTables Buttons -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- ================== END DATATABLES CSS ================== -->

    @stack('styles')

    <style>
        /* ===== Bell Container ===== */
        .bell-wrapper {
            position: relative;
            display: inline-block;
        }

        /* ===== Red Dot Indicator ===== */
        .bell-dot {
            position: absolute;
            top: -2px;
            right: -4px;
            width: 8px;
            height: 8px;
            background: #ff3b30;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(255, 59, 48, 0.7);
            animation: dotPulse 2s infinite;
        }

        /* Pulse glow */
        @keyframes dotPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 59, 48, 0.7);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(255, 59, 48, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 59, 48, 0);
            }
        }

        /* ===== Bell Shake ===== */
        @keyframes bellShake {
            0% {
                transform: rotate(0);
            }

            15% {
                transform: rotate(10deg);
            }

            30% {
                transform: rotate(-8deg);
            }

            45% {
                transform: rotate(5deg);
            }

            60% {
                transform: rotate(-3deg);
            }

            75% {
                transform: rotate(2deg);
            }

            100% {
                transform: rotate(0);
            }
        }

        .bell-animate {
            animation: bellShake 1.2s ease-in-out infinite;
            transform-origin: top center;
        }

        /* ===== Badge Pop In ===== */
        .badge-modern {
            border-radius: 50px;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 600;
            animation: badgeIn 0.4s ease forwards;
            transform: scale(0.7);
            opacity: 0;
        }

        @keyframes badgeIn {
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<!--end::Head-->