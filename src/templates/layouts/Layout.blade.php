<?php use Src\Config\AppConfig; ?>

<!DOCTYPE html>
<html lang="{{ AppConfig::LOCALE_HYPHEN }}" data-bs-theme="light">

<head>
    <meta charset="{{ AppConfig::CHARSET }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $props->title }}</title>
    <meta name="description"
        content="Sint incididunt fugiat ex nostrud eu exercitation duis sint cupidatat esse qui aliqua aliquip.">
    <base href="{{ AppConfig::BASE_SERVER }}/">
    <link rel="stylesheet" href="./assets/plugins/overlayscrollbars/overlayscrollbars.min.css">
    <link rel="stylesheet" href="./bootstrap-icons/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./assets/plugins/datatables/datatables.min.css">
    <link rel="stylesheet" href="./assets/plugins/adminlte/adminlte.css">
    <link rel="stylesheet" href="./assets/css/app.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary sidebar-open app-loaded">
    <div class="app-wrapper">
        <div class="app-preloader flex-column justify-content-center align-items-center"></div>
        <AppHeader />
        <AppSidebar />
        <AppMain title="{{ $props->{"content-title"} }}" breadcrumb="{{ $props->{"content-breadcrumb"} }}">
            {{ $props->children }}
        </AppMain>
    </div>
    <script src="./jquery/jquery.min.js" defer></script>
    <script src="./assets/plugins/overlayscrollbars/overlayscrollbars.min.js" defer></script>
    <script src="./bootstrap/js/bootstrap.bundle.min.js" defer></script>
    <script src="./assets/plugins/datatables/datatables.min.js" defer></script>
    <script src="./assets/plugins/adminlte/adminlte.min.js" defer></script>
    <script src="./assets/plugins/sweetalert2/sweetalert2.min.js" defer></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js" defer></script>
    <script src="./assets/js/app.js" defer></script>
</body>

</html>