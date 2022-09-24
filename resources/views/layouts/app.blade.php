<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AIB Question Bank</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        if(window.localStorage.token){
            window.location = '/dashboard'
        }
    </script>
</head>
<body>
    <div id="loader">
        <img src="/loader.svg" alt="">
    </div>
    <div id="app">
        <main>
            @yield('content')
        </main>
    </div>
    <style>
        .card{
            max-width: 400px;
            min-height: 300px;
            padding: 20px;
        }
        #loader{
            position: fixed;
            top: 0;
            left: 0;
            backdrop-filter: blur(4px);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100vw;
            z-index: 999;
            background: #000000d1;
        }
    </style>
    <script src="/js/app.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.7.8/vue.min.js"></script> --}}

    @stack('cjs')

</body>
</html>
