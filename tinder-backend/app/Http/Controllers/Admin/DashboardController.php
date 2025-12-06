<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Swipe;
use App\Models\PopularNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Check admin role
        if (!auth()->user()->hasRole('admin')) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $stats = [
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', Carbon::today())->count(),
            'new_users_week' => User::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'total_swipes_today' => Swipe::whereDate('created_at', Carbon::today())->count(),
            'total_likes' => Swipe::where('type', 'like')->count(),
            'total_dislikes' => Swipe::where('type', 'dislike')->count(),
            'total_matches' => DB::table('swipes as s1')
                ->join('swipes as s2', function ($join) {
                    $join->on('s1.swiper_id', '=', 's2.swiped_id')
                        ->on('s1.swiped_id', '=', 's2.swiper_id');
                })
                ->where('s1.type', 'like')
                ->where('s2.type', 'like')
                ->where('s1.swiper_id', '<', 's1.swiped_id')
                ->count(),
        ];

        $popularUsers = User::withCount(['receivedSwipes as likes_count' => fn($q) => $q->where('type', 'like')])
            ->having('likes_count', '>=', 50)
            ->orderBy('likes_count', 'desc')
            ->limit(5)
            ->get();

        $recentNotifications = PopularNotification::with('user')
            ->orderBy('notified_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'popularUsers', 'recentNotifications'));
    }

    public function users(Request $request)
    {
        $query = User::with('roles')
            ->withCount(['receivedSwipes as likes_count' => fn($q) => $q->where('type', 'like')]);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->role) {
            $query->role($request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function userDetail($id)
    {
        $user = User::with('roles')
            ->withCount(['receivedSwipes as likes_count' => fn($q) => $q->where('type', 'like')])
            ->withCount(['receivedSwipes as dislikes_count' => fn($q) => $q->where('type', 'dislike')])
            ->findOrFail($id);

        $recentSwipes = Swipe::where('swiper_id', $id)
            ->with('swiped:id,name,email')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.user-detail', compact('user', 'recentSwipes'));
    }

    public function toggleBlock($id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = !$user->is_blocked;
        $user->save();

        if ($user->is_blocked) {
            $user->tokens()->delete();
        }

        return back()->with('success', $user->is_blocked ? 'User blocked!' : 'User unblocked!');
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:user,admin']);
        
        $user = User::findOrFail($id);
        $user->syncRoles([$request->role]);

        return back()->with('success', 'Role updated!');
    }

    public function popularUsers()
    {
        $users = User::withCount(['receivedSwipes as likes_count' => fn($q) => $q->where('type', 'like')])
            ->having('likes_count', '>=', 50)
            ->orderBy('likes_count', 'desc')
            ->paginate(15);

        return view('admin.popular-users', compact('users'));
    }

    public function emailLogs()
    {
        $logs = PopularNotification::with('user')
            ->orderBy('notified_at', 'desc')
            ->paginate(20);

        return view('admin.email-logs', compact('logs'));
    }

    public function sendNotification()
    {
        Artisan::call('users:check-popular');
        $output = Artisan::output();

        return back()->with('success', 'Notification check completed! ' . $output);
    }
}
