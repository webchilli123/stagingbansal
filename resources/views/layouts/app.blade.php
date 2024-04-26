<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name') }}</title>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


  <style>
        :root{ --bs-font-sans-serif: "Be Vietnam", system-ui; }
        .form-control:focus{ border-color: var(--bs-primary); }

  </style>
</head>

<body>
 <main>
    @yield('content')
  </main>
  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  @stack('scripts')
</body>
</html>
