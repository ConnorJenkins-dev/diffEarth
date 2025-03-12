<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @vite('resources/css/app.css')
    @vite(['resources/ts/app.ts'])
    <title>{{ config('app.name') }}</title>
</head>
<body>
<div id="app"></div>

</body>
</html>
