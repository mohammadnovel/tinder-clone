<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Swipe;
use App\Models\PopularNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Admin",
 *     description="API Endpoints for admin dashboard (requires admin role)"
 * )
 */
class AdminController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/admin/dashboard",
     *     summary="Get dashboard statistics",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dashboard statistics",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Unauthorized - Admin access required")
     * )
     */
    public function dashboard(): JsonResponse
    {
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', Carbon::today())->count();
        $newUsersWeek = User::where('created_at', '>=', Carbon::now()->subWeek())->count();
        $newUsersMonth = User::where('created_at', '>=', Carbon::now()->subMonth())->count();

        $totalSwipesToday = Swipe::whereDate('created_at', Carbon::today())->count();
        $totalSwipesWeek = Swipe::where('created_at', '>=', Carbon::now()->subWeek())->count();
        $totalSwipesMonth = Swipe::where('created_at', '>=', Carbon::now()->subMonth())->count();

        $totalLikes = Swipe::where('type', 'like')->count();
        $totalDislikes = Swipe::where('type', 'dislike')->count();

        // Count matches (mutual likes)
        $totalMatches = DB::table('swipes as s1')
            ->join('swipes as s2', function ($join) {
                $join->on('s1.swiper_id', '=', 's2.swiped_id')
                    ->on('s1.swiped_id', '=', 's2.swiper_id');
            })
            ->where('s1.type', 'like')
            ->where('s2.type', 'like')
            ->where('s1.swiper_id', '<', 's1.swiped_id')
            ->count();

        // Popular users count (50+ likes)
        $popularUsersCount = User::withCount(['receivedSwipes as likes_count' => function ($query) {
            $query->where('type', 'like');
        }])->having('likes_count', '>=', 50)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'users' => [
                    'total' => $totalUsers,
                    'new_today' => $newUsersToday,
                    'new_this_week' => $newUsersWeek,
                    'new_this_month' => $newUsersMonth,
                ],
                'swipes' => [
                    'today' => $totalSwipesToday,
                    'this_week' => $totalSwipesWeek,
                    'this_month' => $totalSwipesMonth,
                ],
                'interactions' => [
                    'total_likes' => $totalLikes,
                    'total_dislikes' => $totalDislikes,
                    'total_matches' => $totalMatches,
                ],
                'popular_users_count' => $popularUsersCount,
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/users",
     *     summary="Get all users with pagination",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="page", in="query", description="Page number", @OA\Schema(type="integer", default=1)),
     *     @OA\Parameter(name="per_page", in="query", description="Items per page", @OA\Schema(type="integer", default=15)),
     *     @OA\Parameter(name="search", in="query", description="Search by name or email", @OA\Schema(type="string")),
     *     @OA\Parameter(name="role", in="query", description="Filter by role (user/admin)", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="List of users with pagination")
     * )
     */
    public function users(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $role = $request->get('role');

        $query = User::with('roles')
            ->withCount(['receivedSwipes as likes_count' => function ($query) {
                $query->where('type', 'like');
            }])
            ->withCount(['receivedSwipes as dislikes_count' => function ($query) {
                $query->where('type', 'dislike');
            }])
            ->withCount('swipes as total_swipes');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->role($role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/users/{id}",
     *     summary="Get user detail",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="User ID", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User detail"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function userDetail(int $id): JsonResponse
    {
        $user = User::with('roles')
            ->withCount(['receivedSwipes as likes_count' => function ($query) {
                $query->where('type', 'like');
            }])
            ->withCount(['receivedSwipes as dislikes_count' => function ($query) {
                $query->where('type', 'dislike');
            }])
            ->withCount('swipes as total_swipes')
            ->findOrFail($id);

        // Get recent swipes by this user
        $recentSwipes = Swipe::where('swiper_id', $id)
            ->with('swiped:id,name,pictures')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent likes received
        $recentLikesReceived = Swipe::where('swiped_id', $id)
            ->where('type', 'like')
            ->with('swiper:id,name,pictures')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'recent_swipes' => $recentSwipes,
                'recent_likes_received' => $recentLikesReceived,
            ],
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/users/{id}/role",
     *     summary="Update user role",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role"},
     *             @OA\Property(property="role", type="string", enum={"user", "admin"}, example="admin")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Role updated successfully"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function updateRole(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'role' => 'required|string|in:user,admin',
        ]);

        $user = User::findOrFail($id);

        // Sync roles (remove all and assign new)
        $user->syncRoles([$request->role]);

        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully',
            'data' => $user->load('roles'),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/users/{id}/block",
     *     summary="Block or unblock user",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"blocked"},
     *             @OA\Property(property="blocked", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="User block status updated")
     * )
     */
    public function blockUser(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'blocked' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->is_blocked = $request->blocked;
        $user->save();

        // Revoke all tokens if blocked
        if ($request->blocked) {
            $user->tokens()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => $request->blocked ? 'User blocked successfully' : 'User unblocked successfully',
            'data' => $user,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/popular-users",
     *     summary="Get users with 50+ likes",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="min_likes", in="query", description="Minimum likes threshold", @OA\Schema(type="integer", default=50)),
     *     @OA\Response(response=200, description="List of popular users")
     * )
     */
    public function popularUsers(Request $request): JsonResponse
    {
        $minLikes = $request->get('min_likes', 50);

        $popularUsers = User::withCount(['receivedSwipes as likes_count' => function ($query) {
            $query->where('type', 'like');
        }])
            ->having('likes_count', '>=', $minLikes)
            ->orderBy('likes_count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'users' => $popularUsers,
                'count' => $popularUsers->count(),
                'threshold' => $minLikes,
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/email-logs",
     *     summary="Get email notification logs",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", default=1)),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=20)),
     *     @OA\Response(response=200, description="Email notification logs with pagination")
     * )
     */
    public function emailLogs(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 20);

        $logs = PopularNotification::with('user:id,name,email')
            ->orderBy('notified_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $logs,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/send-popular-notification",
     *     summary="Manually trigger popular users notification",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Notification triggered")
     * )
     */
    public function sendPopularNotification(): JsonResponse
    {
        Artisan::call('users:check-popular');
        $output = Artisan::output();

        return response()->json([
            'success' => true,
            'message' => 'Popular users check triggered successfully',
            'output' => trim($output),
        ]);
    }
}