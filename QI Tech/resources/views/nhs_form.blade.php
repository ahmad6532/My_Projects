<!-- resources/views/specific-page.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
    @vite(['resources/css/app.css'])
    <link href="{{ asset('v2/fonts/LitteraText/stylesheet.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('v2/css/main.css') }}">
</head>
<body>
    <div id="react-root"></div>
    @viteReactRefresh
    @vite('resources/js/nhs_page.tsx')
</body>
</html>
