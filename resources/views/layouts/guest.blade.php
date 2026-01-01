<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SEOStory') }} - AI Competitive Intelligence</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lexend:wght@400;600;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- tailwind -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
                -webkit-font-smoothing: antialiased;
            }
            h1, h2, h3, .font-heading {
                font-family: 'Lexend', sans-serif;
            }
            /* Custom Scrollbar for Dark Mode */
            ::-webkit-scrollbar {
                width: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #030712;
            }
            ::-webkit-scrollbar-thumb {
                background: #312e81;
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #4338ca;
            }
        </style>

        @livewireStyles
    </head>
    <body class="bg-[#030712] text-slate-200 antialiased selection:bg-indigo-500/30 selection:text-indigo-200">
        
        <div class="min-h-screen">
            {{ $slot }}
        </div>

        @livewireScripts

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const elements = document.querySelectorAll('.animate-on-load');
                elements.forEach((el, i) => {
                    setTimeout(() => {
                        el.classList.add('opacity-100', 'translate-y-0');
                    }, i * 150);
                });
            });
        </script>
    </body>
</html>