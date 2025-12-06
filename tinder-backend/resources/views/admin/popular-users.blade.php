@extends('admin.layouts.app')
@section('title', 'Popular Users')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">🔥 Popular Users (50+ Likes)</h1>
    <form action="{{ route('admin.send-notification') }}" method="POST">
        @csrf
        <button type="submit" class="bg-gradient-to-r from-pink-500 to-red-500 text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
            📧 Send Notification
        </button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Rank</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Likes</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($users as $index => $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-2xl">
                    @if($users->firstItem() + $index == 1) 🥇
                    @elseif($users->firstItem() + $index == 2) 🥈
                    @elseif($users->firstItem() + $index == 3) 🥉
                    @else <span class="text-gray-400 text-lg">#{{ $users->firstItem() + $index }}</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-red-400 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                <td class="px-6 py-4">
                    <span class="bg-pink-100 text-pink-600 px-4 py-2 rounded-full font-bold">
                        ❤️ {{ $user->likes_count }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.users.detail', $user->id) }}" class="text-pink-500 hover:text-pink-700 font-medium">View →</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">No popular users yet (50+ likes required).</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
