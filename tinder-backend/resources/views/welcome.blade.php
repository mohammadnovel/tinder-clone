<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tinder Clone API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a5a 50%, #ff8e53 100%); }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .animate-float { animation: float 3s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Flash Message -->
    @if(session('success'))
    <div class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" id="flash-message">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('flash-message').style.display = 'none';
        }, 5000);
    </script>
    @endif

    <!-- Hero Section -->
    <div class="gradient-bg text-white">
        <nav class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <span class="text-3xl">🔥</span>
                <span class="text-xl font-bold">Tinder Clone</span>
            </div>
            <div class="space-x-4">
                <a href="/api/documentation" class="hover:text-pink-200 transition">API Docs</a>
                @auth
                    <span class="text-pink-200">Hi, {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-white/20 px-4 py-2 rounded-full hover:bg-white/30 transition">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="/login" class="bg-white text-pink-500 px-4 py-2 rounded-full font-semibold hover:bg-pink-100 transition">
                        Login
                    </a>
                @endauth
            </div>
        </nav>

        <div class="max-w-7xl mx-auto px-4 py-20 text-center">
            <div class="animate-float text-8xl mb-6">🔥</div>
            <h1 class="text-5xl font-bold mb-4">Tinder Clone API</h1>
            <p class="text-xl text-pink-100 mb-8 max-w-2xl mx-auto">
                A complete backend API for dating applications built with Laravel. 
                Features authentication, matching, swiping, and admin dashboard.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="/api/documentation" 
                   class="bg-white text-pink-500 px-8 py-3 rounded-full font-bold hover:bg-pink-100 transition shadow-lg">
                    📚 View API Docs
                </a>
                @auth
                    @if(Auth::user()->hasRole('admin'))
                    <a href="/admin" 
                       class="border-2 border-white text-white px-8 py-3 rounded-full font-bold hover:bg-white hover:text-pink-500 transition">
                        🔐 Admin Panel
                    </a>
                    @endif
                @else
                    <a href="/login" 
                       class="border-2 border-white text-white px-8 py-3 rounded-full font-bold hover:bg-white hover:text-pink-500 transition">
                        🔐 Login
                    </a>
                @endauth
            </div>
        </div>

        <!-- Wave Divider -->
        <svg viewBox="0 0 1440 120" class="w-full">
            <path fill="#f9fafb" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        </svg>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">✨ Features</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl p-6 shadow-lg card-hover transition duration-300">
                <div class="text-4xl mb-4">🔐</div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Authentication</h3>
                <p class="text-gray-600">Complete auth system with register, login, logout using Laravel Sanctum tokens.</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg card-hover transition duration-300">
                <div class="text-4xl mb-4">💕</div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Swipe & Match</h3>
                <p class="text-gray-600">Like or dislike users, get matches when both users like each other.</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg card-hover transition duration-300">
                <div class="text-4xl mb-4">👥</div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">User Discovery</h3>
                <p class="text-gray-600">Browse recommended people with pagination, excluding already swiped users.</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg card-hover transition duration-300">
                <div class="text-4xl mb-4">👑</div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Admin Dashboard</h3>
                <p class="text-gray-600">Full admin panel to manage users, view statistics, and monitor activity.</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg card-hover transition duration-300">
                <div class="text-4xl mb-4">📧</div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Email Notifications</h3>
                <p class="text-gray-600">Automated email alerts for popular users (50+ likes) via Mailtrap.</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg card-hover transition duration-300">
                <div class="text-4xl mb-4">⏰</div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Cronjob</h3>
                <p class="text-gray-600">Scheduled tasks running every minute to check and notify popular users.</p>
            </div>
        </div>
    </div>

    <!-- API Endpoints Section -->
    <div class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">📡 API Endpoints</h2>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                        <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-sm mr-2">AUTH</span>
                        Authentication
                    </h3>
                    <div class="space-y-2 font-mono text-sm">
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-green-500 text-white px-2 py-1 rounded text-xs mr-3">POST</span>
                            <span class="text-gray-600">/api/v1/auth/register</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-green-500 text-white px-2 py-1 rounded text-xs mr-3">POST</span>
                            <span class="text-gray-600">/api/v1/auth/login</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-green-500 text-white px-2 py-1 rounded text-xs mr-3">POST</span>
                            <span class="text-gray-600">/api/v1/auth/logout</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs mr-3">GET</span>
                            <span class="text-gray-600">/api/v1/auth/me</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                        <span class="bg-pink-100 text-pink-600 px-2 py-1 rounded text-sm mr-2">PEOPLE</span>
                        Discovery & Lists
                    </h3>
                    <div class="space-y-2 font-mono text-sm">
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs mr-3">GET</span>
                            <span class="text-gray-600">/api/v1/people</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs mr-3">GET</span>
                            <span class="text-gray-600">/api/v1/people/liked</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs mr-3">GET</span>
                            <span class="text-gray-600">/api/v1/people/disliked</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs mr-3">GET</span>
                            <span class="text-gray-600">/api/v1/people/matches</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm mr-2">SWIPE</span>
                        Like & Dislike
                    </h3>
                    <div class="space-y-2 font-mono text-sm">
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-green-500 text-white px-2 py-1 rounded text-xs mr-3">POST</span>
                            <span class="text-gray-600">/api/v1/swipe/like</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-green-500 text-white px-2 py-1 rounded text-xs mr-3">POST</span>
                            <span class="text-gray-600">/api/v1/swipe/dislike</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs mr-3">GET</span>
                            <span class="text-gray-600">/api/v1/swipe/stats</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                        <span class="bg-purple-100 text-purple-600 px-2 py-1 rounded text-sm mr-2">ADMIN</span>
                        Dashboard
                    </h3>
                    <div class="space-y-2 font-mono text-sm">
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs mr-3">GET</span>
                            <span class="text-gray-600">/api/v1/admin/dashboard</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs mr-3">GET</span>
                            <span class="text-gray-600">/api/v1/admin/users</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs mr-3">GET</span>
                            <span class="text-gray-600">/api/v1/admin/popular-users</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <span class="bg-green-500 text-white px-2 py-1 rounded text-xs mr-3">POST</span>
                            <span class="text-gray-600">/api/v1/admin/send-notification</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Demo Accounts Section -->
    <div class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">🔑 Demo Accounts</h2>
        
        <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <div class="bg-gradient-to-br from-red-500 to-pink-500 rounded-xl p-6 text-white shadow-lg">
                <div class="text-3xl mb-2">👑</div>
                <h3 class="text-xl font-bold">Admin</h3>
                <p class="text-pink-100 text-sm mb-4">Full dashboard access</p>
                <div class="bg-white/20 rounded p-3 font-mono text-sm">
                    <p>admin@example.com</p>
                    <p>admin123</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl p-6 text-white shadow-lg">
                <div class="text-3xl mb-2">👨</div>
                <h3 class="text-xl font-bold">User (John)</h3>
                <p class="text-blue-100 text-sm mb-4">Regular user account</p>
                <div class="bg-white/20 rounded p-3 font-mono text-sm">
                    <p>john@example.com</p>
                    <p>password123</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl p-6 text-white shadow-lg">
                <div class="text-3xl mb-2">👩</div>
                <h3 class="text-xl font-bold">User (Jane)</h3>
                <p class="text-purple-100 text-sm mb-4">Regular user account</p>
                <div class="bg-white/20 rounded p-3 font-mono text-sm">
                    <p>jane@example.com</p>
                    <p>password123</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tech Stack Section -->
    <div class="bg-gray-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">🛠️ Tech Stack</h2>
            
            <div class="flex flex-wrap justify-center gap-4">
                <span class="bg-gray-700 px-6 py-3 rounded-full">PHP 8.x</span>
                <span class="bg-gray-700 px-6 py-3 rounded-full">Laravel 10.x</span>
                <span class="bg-gray-700 px-6 py-3 rounded-full">Laravel Sanctum</span>
                <span class="bg-gray-700 px-6 py-3 rounded-full">Spatie Permission</span>
                <span class="bg-gray-700 px-6 py-3 rounded-full">SQLite / MySQL</span>
                <span class="bg-gray-700 px-6 py-3 rounded-full">Mailtrap</span>
                <span class="bg-gray-700 px-6 py-3 rounded-full">Swagger/OpenAPI</span>
                <span class="bg-gray-700 px-6 py-3 rounded-full">TailwindCSS</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex justify-center items-center space-x-2 mb-4">
                <span class="text-2xl">🔥</span>
                <span class="text-white font-bold">Tinder Clone API</span>
            </div>
            <p class="text-sm">
                Built with ❤️ using Laravel | 
                <a href="https://github.com/mohammadnovel/tinder-clone" class="text-pink-400 hover:underline" target="_blank">GitHub</a>
            </p>
            <p class="text-xs mt-2">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} | PHP v{{ PHP_VERSION }}
            </p>
        </div>
    </footer>
</body>
</html>
