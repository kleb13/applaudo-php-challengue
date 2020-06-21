<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\MovieStore;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Http\Resources\MovieCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $movies;
    protected $movieStore;
    public function __construct(Movie $movies,MovieStore $movieStore)
    {
        $this->movies = $movies;
        $this->movieStore = $movieStore;
    }

    /**
     */
    public function index(Request $request)
    {
        $query = $this->movies
                    ->order($request->get("order_by",""))
                    ->searchByTitle($request->get("search",""));

        return new MovieCollection($query->paginate(10));
    }

    public function show($id)
    {
        return new \App\Http\Resources\Movie($this->movies->find($id));
    }

    public function buy($id)
    {
        $movie = $this->movies->find($id);
        $result  = $this->movieStore->buy($movie);

        return (new JsonResponse($result))
            ->setStatusCode($result->wasSuccess()?200:400);
    }

    public function rent($id)
    {
        $movie = $this->movies->find($id);
        $result  = $this->movieStore->rent($movie);

        return (new JsonResponse($result))
            ->setStatusCode($result->wasSuccess()?200:400);
    }

    public function return($id)
    {
        $movie = $this->movies->find($id);
        $result  = $this->movieStore->return($movie);

        return (new JsonResponse($result))
            ->setStatusCode($result->wasSuccess()?200:400);
    }
}
