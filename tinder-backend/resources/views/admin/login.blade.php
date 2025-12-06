<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tinder Clone</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-pink-500 via-red-500 to-orange-400 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-pink-500 to-red-500 rounded-full mb-4">
                <span class="text-4xl">🔥</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Welcome Back</h1>
            <p class="text-gray-500 mt-1">Login to Tinder Clone</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition"
                        placeholder="you@example.com">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input type="password" name="password" required
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-pink-500 focus:ring-pink-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <button type="submit" 
                class="w-full bg-gradient-to-r from-pink-500 to-red-500 text-white py-3 rounded-lg font-semibold hover:from-pink-600 hover:to-red-600 transition transform hover:scale-[1.02] active:scale-[0.98] shadow-lg">
                Sign In
            </button>
        </form>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-gray-500">Demo Accounts</span>
            </div>
        </div>

        <!-- Demo Accounts -->
        <div class="space-y-2">
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-100">
                <div>
                    <span class="text-xs font-semibold text-red-600 bg-red-100 px-2 py-1 rounded">ADMIN</span>
                    <p class="text-sm text-gray-600 mt-1 font-mono">admin@example.com</p>
                </div>
                <span class="text-sm text-gray-500 font-mono">admin123</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg border border-blue-100">
                <div>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-2 py-1 rounded">USER</span>
                    <p class="text-sm text-gray-600 mt-1 font-mono">john@example.com</p>
                </div>
                <span class="text-sm text-gray-500 font-mono">password123</span>
            </div>
        </div>

        <!-- Footer Links -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <a href="/" class="hover:text-pink-500 transition">← Back to Home</a>
            <span class="mx-2">•</span>
            <a href="/api/documentation" class="hover:text-pink-500 transition">API Docs</a>
        </div>
    </div>
</body>
</html>
