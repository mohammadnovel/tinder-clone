@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<h1 class="text-3xl font-bold mb-6">📊 Dashboard</h1>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Users</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
            </div>
            <div class="text-4xl">👥</div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">New Today</p>
                <p class="text-3xl font-bold text-green-500">{{ $stats['new_users_today'] }}</p>
            </div>
            <div class="text-4xl">📈</div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Likes</p>
                <p class="text-3xl font-bold text-pink-500">{{ $stats['total_likes'] }}</p>
            </div>
            <div class="text-4xl">❤️</div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Matches</p>
                <p class="text-3xl font-bold text-purple-500">{{ $stats['total_matches'] }}</p>
            </div>
            <div class="text-4xl">💕</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Popular Users -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">🔥 Popular Users (50+ Likes)</h2>
            <a href="{{ route('admin.popular') }}" class="text-pink-500 hover:underline text-sm">View All →</a>
        </div>
        @if($popularUsers->count() > 0)
            <ul class="space-y-3">
                @foreach($popularUsers as $user)
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-red-400 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="bg-pink-100 text-pink-600 px-3 py-1 rounded-full text-sm font-semibold">
                        ❤️ {{ $user->likes_count }}
                    </span>
                </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 text-center py-8">No popular users yet.</p>
        @endif
    </div>

    <!-- Recent Notifications -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">📧 Recent Notifications</h2>
            <form action="{{ route('admin.send-notification') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-gradient-to-r from-pink-500 to-red-500 text-white px-4 py-2 rounded-lg text-sm hover:opacity-90 transition">
                    🚀 Send Now
                </button>
            </form>
        </div>
        @if($recentNotifications->count() > 0)
            <ul class="space-y-3">
                @foreach($recentNotifications as $notif)
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">{{ $notif->user->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500">{{ $notif->likes_count }} likes</p>
                    </div>
                    <span class="text-gray-400 text-sm">{{ $notif->notified_at->diffForHumans() }}</span>
                </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 text-center py-8">No notifications sent yet.</p>
        @endif
    </div>
</div>

<!-- Quick Stats -->
<div class="mt-6 bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">📈 This Week Stats</h2>
    <div class="grid grid-cols-3 gap-4">
        <div class="text-center p-4 bg-blue-50 rounded-lg">
            <p class="text-2xl font-bold text-blue-600">{{ $stats['new_users_week'] }}</p>
            <p class="text-sm text-gray-600">New Users</p>
        </div>
        <div class="text-center p-4 bg-green-50 rounded-lg">
            <p class="text-2xl font-bold text-green-600">{{ $stats['total_swipes_today'] }}</p>
            <p class="text-sm text-gray-600">Swipes Today</p>
        </div>
        <div class="text-center p-4 bg-red-50 rounded-lg">
            <p class="text-2xl font-bold text-red-600">{{ $stats['total_dislikes'] }}</p>
            <p class="text-sm text-gray-600">Total Dislikes</p>
        </div>
    </div>
</div>
@endsection
