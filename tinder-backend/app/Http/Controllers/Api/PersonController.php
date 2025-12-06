<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use App\Models\Swipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 *     name="People",
 *     description="API Endpoints for managing people/profiles"
 * )
 */
class PersonController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/people",
     *     summary="Get list of recommended people",
     *     tags={"People"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Person")),
     *             @OA\Property(property="meta", ref="#/components/schemas/Pagination")
     *         )
     *     )
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page', 10);
        $user = $request->user();

        $people = Person::notSwipedBy($user->id)
            ->inRandomOrder()
            ->paginate($perPage);

        return PersonResource::collection($people);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/people/{id}",
     *     summary="Get a specific person",
     *     tags={"People"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/Person")
     *     ),
     *     @OA\Response(response=404, description="Person not found")
     * )
     */
    public function show(Person $person): PersonResource
    {
        return new PersonResource($person);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/people/liked",
     *     summary="Get liked people list",
     *     tags={"People"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Person"))
     *         )
     *     )
     * )
     */
    public function liked(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page', 20);
        $user = $request->user();

        $likedIds = $user->getLikedPeopleIds();

        $likedPeople = Person::whereIn('id', $likedIds)
            ->paginate($perPage);

        return PersonResource::collection($likedPeople);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/people/disliked",
     *     summary="Get disliked people list",
     *     tags={"People"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Person"))
     *         )
     *     )
     * )
     */
    public function disliked(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page', 20);
        $user = $request->user();

        $dislikedIds = $user->getDislikedPeopleIds();

        $dislikedPeople = Person::whereIn('id', $dislikedIds)
            ->paginate($perPage);

        return PersonResource::collection($dislikedPeople);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/people/matches",
     *     summary="Get matches (mutual likes)",
     *     tags={"People"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Person"))
     *         )
     *     )
     * )
     */
    public function matches(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get actual matches from matches table
        $matchedPersonIds = \App\Models\UserMatch::where('user_id', $user->id)
            ->pluck('person_id')
            ->toArray();

        $matches = Person::whereIn('id', $matchedPersonIds)->get();

        return response()->json([
            'success' => true,
            'data' => PersonResource::collection($matches),
        ]);
    }
}
