<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $company->short_name }}</title>
		<link rel="icon" type="image/png" href="{{ $company->small_light_logo_url }}">
		<meta name="msapplication-TileImage" href="{{ $company->small_light_logo_url }}">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700&display=swap">

		@if($themeMode == 'dark')
		<link rel="stylesheet" href="{{ asset('css/antd.dark.css') }}">
		@else
		<link rel="stylesheet" href="{{ asset('css/antd.css') }}">
		@endif
		<link rel="stylesheet" href="{{ asset('css/app.css') }}">
		<link rel="stylesheet" href="{{ asset('css/pos_invoice_css.css') }}">
    </head>
    <body class="{{ $themeMode == 'dark' ? 'dark_theme' : 'light_theme' }}">
        <div id="app"></div>
        <script>
            window.config = {
                'path': '{{ url('/') }}',
                'invoice_url': "{{ route('api.extra.pdf.v1', '') }}",
                'pos_invoice_css': "{{ asset('css/pos_invoice_css.css') }}",
                'verify_purchase_background': "{{ asset('images/verify_purchase_background.svg') }}",
                'login_background': "{{ asset('images/login_background.svg') }}",
                'perPage': 10,
				'product_name': "{{ $appName }}",
				'product_version': "{{ $appVersion }}",
				'modules': @json($enabledModules),
				'installed_modules': @json($installedModules),
				'theme_mode': "{{ $themeMode }}",
				'appChecking': true,
				'app_version': "{{ $appVersion }}",
				'app_env': "{{ $appEnv }}",
            };
        </script>
        <script src="{{asset('js/app.js')}}"></script>
    </body>
</html>