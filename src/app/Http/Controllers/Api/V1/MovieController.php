<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\MovieStore;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Http\Resources\MovieCollection;
use App\Scopes\AvailabilityScope;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * This controller is aimed to show only available movies
 * Class MovieController
 * @package App\Http\Controllers\Api\V1
 */
class MovieController extends Controller
{
    /**
     * @var Movie
     */
    protected $movies;
    /**
     * @var MovieStore
     */
    protected $movieStore;

    /**
     * MovieController constructor.
     * @param Movie $movies
     * @param MovieStore $movieStore
     */
    public function __construct(Movie $movies,MovieStore $movieStore)
    {
        $this->movies = $movies;
        $this->movieStore = $movieStore;
    }

    /**
     * Return a list of available movies
     * you can order by popularity or title asc or desc
     * @param Request $request
     * @return MovieCollection
     */
    public function index(Request $request)
    {
        $query = $this->movies
                    ->order($request->get("order_by",""))
                    ->searchByTitle($request->get("search",""));

        return new MovieCollection($query->paginate(10));
    }

    /**
     * Return a Detail for a movie
     * @param $id
     * @return \App\Http\Resources\Movie
     */
    public function show($id)
    {
        return new \App\Http\Resources\Movie($this->movies->findOrFail($id));
    }

    /**
     * Buy a movie
     * It could return an error in case there is not enough stock
     * @param $id
     * @return JsonResponse
     */
    public function buy($id)
    {
        $movie = $this->movies->findOrFail($id);
        $result  = $this->movieStore->buy($movie);

        return (new JsonResponse($result))
            ->setStatusCode($result->wasSuccess()?200:400);
    }

    /**
     * rent a movie
     * It could return an error if already was rented by the user
     * @param $id
     * @return JsonResponse
     */
    public function rent($id)
    {
        $movie = $this->movies->findOrFail($id);
        $result  = $this->movieStore->rent($movie);

        return (new JsonResponse($result))
            ->setStatusCode($result->wasSuccess()?200:400);
    }

    /**
     * Return a movie
     * If there is not a rental for this movie will thrown an error
     * @param $id
     * @return JsonResponse
     */
    public function return($id)
    {
        $movie = $this->movies->withoutGlobalScope(AvailabilityScope::class)->findOrFail($id);
        $result  = $this->movieStore->return($movie);

        return (new JsonResponse($result))
            ->setStatusCode($result->wasSuccess()?200:400);
    }
}
