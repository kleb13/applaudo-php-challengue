<?php

namespace  App\Http\Controllers\Api\V1;

use App\Models\Like;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    protected  $movies;
    protected $auth;

    public function __construct(Movie $movies, Auth $auth)
    {
        $this->movies = $movies;
        $this->auth = $auth;
    }

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
