<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @vite('resources/css/app.css')
    <title>{{ config('app.name') }}</title>
</head>
<body>
<div id="app"></div>

@vite(['resources/ts/app.ts'])
</body>
</html>
