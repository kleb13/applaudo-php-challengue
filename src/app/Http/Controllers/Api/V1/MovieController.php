<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Http\Resources\MovieCollection;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $movies;

    public function __construct(Movie $movies)
    {
        $this->movies = $movies;
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

}
