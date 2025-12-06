@extends('admin.layouts.app')
@section('title', 'User Detail')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users') }}" class="text-pink-500 hover:text-pink-700">← Back to Users</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- User Info -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="text-center mb-6">
            @if($user->pictures && count($user->pictures) > 0)
                <img src="{{ $user->pictures[0] }}" class="w-24 h-24 rounded-full mx-auto object-cover shadow-lg">
            @else
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-pink-400 to-red-400 mx-auto flex items-center justify-center text-white text-3xl font-bold">
                    {{ substr($user->name, 0, 1) }}
                </div>
            @endif
            <h2 class="text-xl font-bold mt-4 text-gray-800">{{ $user->name }}</h2>
            <p class="text-gray-500">{{ $user->email }}</p>
        </div>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between p-2 bg-gray-50 rounded">
                <span class="text-gray-500">Age</span>
                <span class="font-medium">{{ $user->age }} years</span>
            </div>
            <div class="flex justify-between p-2 bg-gray-50 rounded">
                <span class="text-gray-500">Location</span>
                <span class="font-medium">{{ $user->location ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between p-2 bg-gray-50 rounded">
                <span class="text-gray-500">Bio</span>
                <span class="font-medium">{{ Str::limit($user->bio, 30) ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between p-2 bg-gray-50 rounded">
                <span class="text-gray-500">Joined</span>
                <span class="font-medium">{{ $user->created_at->format('M d, Y') }}</span>
            </div>
        </div>

        <div class="mt-6 space-y-2">
            <form action="{{ route('admin.users.block', $user->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2 rounded-lg font-semibold transition {{ $user->is_blocked ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-red-500 hover:bg-red-600 text-white' }}">
                    {{ $user->is_blocked ? '✓ Unblock User' : '✕ Block User' }}
                </button>
            </form>
            <form action="{{ route('admin.users.role', $user->id) }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="{{ $user->hasRole('admin') ? 'user' : 'admin' }}">
                <button type="submit" class="w-full py-2 rounded-lg bg-purple-500 hover:bg-purple-600 text-white font-semibold transition">
                    Make {{ $user->hasRole('admin') ? 'User' : 'Admin' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold mb-4 text-gray-800">📊 Statistics</h3>
        <div class="space-y-4">
            <div class="p-4 bg-pink-50 rounded-lg text-center">
                <p class="text-3xl font-bold text-pink-500">❤️ {{ $user->likes_count ?? 0 }}</p>
                <p class="text-sm text-gray-600">Likes Received</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg text-center">
                <p class="text-3xl font-bold text-gray-500">👎 {{ $user->dislikes_count ?? 0 }}</p>
                <p class="text-sm text-gray-600">Dislikes Received</p>
            </div>
            <div class="p-4 bg-blue-50 rounded-lg text-center">
                <p class="text-xl font-bold text-blue-600">{{ ucfirst($user->roles->pluck('name')->join(', ')) }}</p>
                <p class="text-sm text-gray-600">Role</p>
            </div>
            <div class="p-4 {{ $user->is_blocked ? 'bg-red-50' : 'bg-green-50' }} rounded-lg text-center">
                <p class="text-xl font-bold {{ $user->is_blocked ? 'text-red-600' : 'text-green-600' }}">
                    {{ $user->is_blocked ? 'Blocked' : 'Active' }}
                </p>
                <p class="text-sm text-gray-600">Status</p>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold mb-4 text-gray-800">🕐 Recent Swipes</h3>
        @if($recentSwipes->count() > 0)
            <ul class="space-y-2">
                @foreach($recentSwipes as $swipe)
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">{{ $swipe->swiped->name ?? 'Unknown' }}</span>
                    <span class="{{ $swipe->type == 'like' ? 'text-pink-500' : 'text-gray-400' }} text-xl">
                        {{ $swipe->type == 'like' ? '❤️' : '👎' }}
                    </span>
                </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 text-center py-8">No swipes yet.</p>
        @endif
    </div>
</div>
@endsection
