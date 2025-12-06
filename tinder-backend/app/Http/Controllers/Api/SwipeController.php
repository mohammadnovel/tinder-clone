<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Swipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Swipes",
 *     description="API Endpoints for swiping (like/dislike)"
 * )
 */
class SwipeController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/swipe/like",
     *     summary="Like a person",
     *     tags={"Swipes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"person_id"},
     *             @OA\Property(property="person_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successfully liked",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Person liked successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="swipe_id", type="integer"),
     *                 @OA\Property(property="is_match", type="boolean")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function like(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'person_id' => 'required|exists:people,id',
        ]);

        $user = $request->user();
        $personId = $validated['person_id'];

        $swipe = Swipe::updateOrCreate(
            [
                'swiper_id' => $user->id,
                'swiped_id' => $personId,
            ],
            ['type' => 'like']
        );

        // Check if this is a match (in a real app, would check mutual likes)
        // For demo purposes, randomly determine if it's a match
        $isMatch = rand(0, 100) < 20; // 20% chance of match

        // Save match to database if it occurred
        if ($isMatch) {
            \App\Models\UserMatch::firstOrCreate([
                'user_id' => $user->id,
                'person_id' => $personId,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Person liked successfully',
            'data' => [
                'swipe_id' => $swipe->id,
                'is_match' => $isMatch,
            ],
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/swipe/dislike",
     *     summary="Dislike a person",
     *     tags={"Swipes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"person_id"},
     *             @OA\Property(property="person_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully disliked",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Person disliked successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="swipe_id", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function dislike(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'person_id' => 'required|exists:people,id',
        ]);

        $user = $request->user();
        $personId = $validated['person_id'];

        $swipe = Swipe::updateOrCreate(
            [
                'swiper_id' => $user->id,
                'swiped_id' => $personId,
            ],
            ['type' => 'dislike']
        );

        return response()->json([
            'success' => true,
            'message' => 'Person disliked successfully',
            'data' => [
                'swipe_id' => $swipe->id,
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/swipe/stats",
     *     summary="Get swipe statistics",
     *     tags={"Swipes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="total_likes_given", type="integer"),
     *                 @OA\Property(property="total_dislikes_given", type="integer"),
     *                 @OA\Property(property="total_swipes", type="integer"),
     *                 @OA\Property(property="remaining_profiles", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();

        $totalLikes = Swipe::where('swiper_id', $user->id)->likes()->count();
        $totalDislikes = Swipe::where('swiper_id', $user->id)->dislikes()->count();
        $totalPeople = Person::count();
        $swipedCount = Swipe::where('swiper_id', $user->id)->count();

        // Calculate matches from matches table
        $totalMatches = \App\Models\UserMatch::where('user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_likes_given' => $totalLikes,
                'total_dislikes_given' => $totalDislikes,
                'total_swipes' => $totalLikes + $totalDislikes,
                'total_matches' => $totalMatches,
                'total_profiles' => $totalPeople,
                'remaining_profiles' => $totalPeople - $swipedCount,
            ],
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/swipe/{id}",
     *     summary="Undo a swipe",
     *     tags={"Swipes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Swipe undone successfully"
     *     )
     * )
     */
    public function undo(Request $request, Swipe $swipe): JsonResponse
    {
        $user = $request->user();

        if ($swipe->swiper_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $swipe->delete();

        return response()->json([
            'success' => true,
            'message' => 'Swipe undone successfully',
        ]);
    }
}
