@extends('admin.layouts.app')
@section('title', 'Users')

@section('content')
<h1 class="text-3xl font-bold mb-6">👥 User Management</h1>

<!-- Search -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..."
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
        <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
        </select>
        <button type="submit" class="bg-gradient-to-r from-pink-500 to-red-500 text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
            🔍 Search
        </button>
        @if(request('search') || request('role'))
        <a href="{{ route('admin.users') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">Clear</a>
        @endif
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Likes</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-gray-500">{{ $user->id }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-red-400 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    @foreach($user->roles as $role)
                        <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $role->name == 'admin' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                            {{ ucfirst($role->name) }}
                        </span>
                    @endforeach
                </td>
                <td class="px-6 py-4">
                    <span class="text-pink-500 font-semibold">❤️ {{ $user->likes_count ?? 0 }}</span>
                </td>
                <td class="px-6 py-4">
                    @if($user->is_blocked)
                        <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-full font-semibold">Blocked</span>
                    @else
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded-full font-semibold">Active</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-500 text-sm">{{ $user->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.users.detail', $user->id) }}" class="text-pink-500 hover:text-pink-700 font-medium">View →</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
