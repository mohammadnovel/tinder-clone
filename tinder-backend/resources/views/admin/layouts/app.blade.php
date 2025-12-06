<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Tinder Clone</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-gradient-to-r from-pink-500 to-red-500 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold flex items-center">
                        <span class="text-2xl mr-2">🔥</span> Tinder Admin
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-pink-200 {{ request()->routeIs('admin.dashboard') ? 'text-white font-semibold' : 'text-pink-100' }}">Dashboard</a>
                    <a href="{{ route('admin.users') }}" class="hover:text-pink-200 {{ request()->routeIs('admin.users*') ? 'text-white font-semibold' : 'text-pink-100' }}">Users</a>
                    <a href="{{ route('admin.popular') }}" class="hover:text-pink-200 {{ request()->routeIs('admin.popular') ? 'text-white font-semibold' : 'text-pink-100' }}">Popular Users</a>
                    <a href="{{ route('admin.email-logs') }}" class="hover:text-pink-200 {{ request()->routeIs('admin.email-logs') ? 'text-white font-semibold' : 'text-pink-100' }}">Email Logs</a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-pink-100">👤 {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-white/20 px-4 py-2 rounded-lg hover:bg-white/30 transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
