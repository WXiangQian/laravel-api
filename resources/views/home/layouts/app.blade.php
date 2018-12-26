<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="keywords" content="{{ config('blog.meta.keywords') }}">
  <meta name="description" content="{{ config('blog.meta.description') }}">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" href="{{ config('blog.default_icon') }}">

  <title>@yield('title', config('app.name'))</title>

  <link rel="stylesheet" href="{{ mix('css/app.css') }}">

  <!-- Scripts -->
  <script>
    window.Language = '{{ config('app.locale') }}';
    window.Laravel = <?php echo json_encode([
          'csrfToken' => csrf_token(),
      ]); ?>
  </script>

  @yield('styles')
</head>
<body>
<div id="app">

  <div class="main" style="margin: 20px;">
    @yield('content')
  </div>

</div>

<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>`

@yield('scripts')


</body>
</html>