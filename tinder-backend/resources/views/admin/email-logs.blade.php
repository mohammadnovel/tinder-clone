@extends('admin.layouts.app')
@section('title', 'Email Logs')

@section('content')
<h1 class="text-3xl font-bold mb-6">📧 Email Notification Logs</h1>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Likes Count</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Admin Email</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Notified At</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-gray-500">{{ $log->id }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-pink-400 to-red-400 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            {{ substr($log->user->name ?? '?', 0, 1) }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $log->user->name ?? 'Unknown' }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-pink-500 font-semibold">❤️ {{ $log->likes_count }}</span>
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $log->admin_email }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $log->notified_at->format('M d, Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">No notifications sent yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $logs->links() }}
</div>
@endsection
