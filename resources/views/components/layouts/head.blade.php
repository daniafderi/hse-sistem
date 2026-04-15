@props(['title'])

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="300">
    <title>{{ $title ?? config('app.name') }}</title>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&ampdisplay=swap"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Development version -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Production version -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
