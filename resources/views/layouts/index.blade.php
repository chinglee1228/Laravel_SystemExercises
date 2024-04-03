<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    <!-- 引入 jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- 引入 Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- 引入 Bootstrap JS (确保包括 Popper.js) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

@yield('css')
</head>
<style>
     .custom-alert {
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            min-width: 300px;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
            padding: 15px;
            z-index: 1000;
            /*display: none; /* 默认不显示 */
        }
        .custom-alert.active {
            display: block; /* 激活时显示 */
        }
        .close-btn {
            float: right;
            cursor: pointer;
        }
</style>
<body>
    <main class="m-4">
        <div>
            <h1>@yield('title')</h1>
            <a>Holle! {{Auth::user()->name}}</a>
            <a>did {{Auth::user()->did}}</a>
        </div>
        @yield('main')
    </main>

@yield('script')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

</body>
</html>