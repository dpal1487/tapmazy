<!DOCTYPE html>
<script>
    window.addEventListener("load", () => {
        const html = document.getElementsByTagName('html')[0];
        const theme = window.localStorage.getItem('theme');
        if (!theme || theme === 'system') {
            html.setAttribute("data-theme", "system")
        } else {
            html.setAttribute("data-theme", theme)
        }
    })
</script>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-layout-mode="light" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name', 'Sumplizy') }}</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Scripts -->
    <link rel="stylesheet" href="{{ url('assets/plugins/global/plugins.bundle.css') }}" />
    @routes
    @vite(['resources/js/app.js', 'resources/sass/app.scss', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
    <script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=explicit"></script>
</head>

<body id="app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default" data-new-gr-c-s-check-loaded="14.1097.0">
    @inertia
    @env('local')
    @endenv
    <script>
        window._asset = '{{ asset("") }}';
    </script>
</body>

</html>
