<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    // ...existing code...
    <link rel="stylesheet" href="{{ asset('build/assets/theme.css') }}">
    <script src="{{ asset('build/assets/app.js') }}" defer></script>

</head>

<body>
    // ...existing code...

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/service-worker.js').then(function (registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function (err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>

</html>