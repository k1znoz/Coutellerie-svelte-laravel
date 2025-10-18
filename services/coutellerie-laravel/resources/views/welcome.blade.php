<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body>
    <div id="app">
        <h1>Coutellerie API</h1>
        <p>Laravel backend is running successfully!</p>
        <p>Check <a href="/api/health">/api/health</a> for API status.</p>
    </div>
</body>
</html>