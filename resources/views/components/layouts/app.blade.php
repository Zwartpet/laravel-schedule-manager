<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{ Vite::useBuildDirectory('vendor/schedule-manager')->withEntryPoints(['resources/css/schedule-manager.css']) }}

    <title>{{ $title ?? config('app.name') . ' - Schedule manager' }}</title>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center min-h-screen flex-col">
<div class="flex items-center justify-center w-full">
    <mail class="flex w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
        {{ $slot }}
    </mail>
</div>
</body>
</html>