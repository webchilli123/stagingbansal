<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <!-- styles -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">


    <!-- fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    
    <style>
        /* :root{ --bs-font-sans-serif: 'Be Vietnam', system-ui; } */

        .selectize-control {
            padding: 0;
        }

        input[readonly]:hover{
            cursor: not-allowed;
        }

        td.details-control:after {
            content: '\f055';
            cursor: pointer;
            font-family: "Font Awesome 5 Free";
            font-weight: bold;
            color: var(--bs-primary);
        }
        tr.shown td.details-control:after {
            content: '\f056';
            cursor: pointer;
            font-family: "Font Awesome 5 Free";
            font-weight: bold;
            color: var(--bs-primary);
        }

        .table>:not(:last-child)>:last-child>*{
            border-bottom-color: inherit;
        }

        .form-control:focus{ border-color: var(--bs-primary); }
        .bg-indigo{ background-color: var(--bs-indigo); }
        div.dataTables_wrapper div.dataTables_filter label {
            color: white;
            background: #0d79ab; 
            padding: 1px;
            border-radius: 4px; 
            padding-left: 6px;
        }

        
    </style>
</head>

<body>

    @include('partials/sidebar')

    <div class="col-md-9 col-xl-10 px-0 ms-md-auto">
     
        @include('partials/top-nav')
       
        <main class="px-4">
            @include('partials/success')
            @include('partials/errors')

            @yield('content')
        </main>
    </div>    
    
    
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        $(document).ready(()=>{
            setTimeout(()=>{ $('#success').remove(); }, 3000);
        });
    </script>
    @stack('scripts')
</body>
</html>
