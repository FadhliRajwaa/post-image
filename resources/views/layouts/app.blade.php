<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Image Poster Generator')</title>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to bottom right, #f8fafc, #f1f5f9);
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        .nav-link {
            position: relative;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, #6366f1, #8b5cf6);
            border-radius: 2px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm sticky top-0 z-40 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="{{ route('home') }}" class="flex items-center">
                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white mr-3 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                        Image Poster Generator
                    </h1>
                </a>
                <nav>
                    <ul class="flex space-x-1">
                        <li>
                            <a href="{{ route('home') }}" 
                               class="nav-link {{ request()->routeIs('home') ? 'active' : '' }} text-gray-700 hover:text-indigo-600 px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin') }}" 
                               class="nav-link {{ request()->routeIs('admin') || request()->routeIs('posters.*') ? 'active' : '' }} text-gray-700 hover:text-indigo-600 px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Admin
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <footer class="bg-white border-t mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="flex items-center justify-center h-8 w-8 rounded-md bg-gradient-to-br from-indigo-600 to-purple-600 text-white mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-semibold">Image Poster Generator</p>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-gray-500 text-sm">
                        &copy; {{ date('Y') }} Image Poster Generator. All rights reserved.
                    </p>
                    <p class="text-gray-400 text-xs mt-1">
                        Dibuat dengan Laravel & Tailwind CSS
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    @stack('scripts')
</body>
</html> 