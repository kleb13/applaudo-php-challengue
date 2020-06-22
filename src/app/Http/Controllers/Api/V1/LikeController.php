<?php

namespace  App\Http\Controllers\Api\V1;

use App\Models\Like;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;

/**
 * Class LikeController
 * @package App\Http\Controllers\Api\V1
 */
class LikeController extends Controller
{
    /**
     * @var Movie
     */
    protected  $movies;
    /**
     * @var Auth
     */
    protected $auth;

    /**
     * LikeController constructor.
     * @param Movie $movies
     * @param Auth $auth
     */
    public function __construct(Movie $movies, Auth $auth)
    {
        $this->movies = $movies;
        $this->auth = $auth;
    }

    /**
     * Create a like for a movie
     * If already liked it returns 400
     * @param $movieId
     * @return JsonResponse|object
     */
    public function store($movieId)
    {
        /**
         * @var Movie $movie
         */
        $movie = $this->movies->findOrFail($movieId);
        if ($movie->hasBeenLikedByUser($this->auth->user()->id)) {
            return (new JsonResource([
                "message" => "Already liked this movie"
            ]))->response()->setStatusCode(400);
        }
        $movie->likes()->save(new Like([
            'user_id' =>$this->auth->user()->id,
            'created_at' => now()
        ]));
    }

    /**
     * Remove a like for a movie
     * @param $movieId
     * @return JsonResponse|object
     */
    public function destroy($movieId)
    {
        /**
         * @var Movie $movie
         */
        $movie = $this->movies->findOrFail($movieId);
        $movie->likesByUser($this->auth->user()->id)->delete();
        return (new JsonResponse(null))->setStatusCode(204);
    }
}
