<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieRequest;
use App\Http\Resources\MovieCollection;
use App\Models\MovieImage;
use App\Models\Movie;
use App\Scopes\AvailabilityScope;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MovieController extends Controller
{

    protected $movies;

    public function __construct(Movie $movies)
    {
        $this->movies = $movies->withoutGlobalScope(AvailabilityScope::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return MovieCollection
     */
    public function index(Request $request)
    {
        $query = $this->movies
            ->order($request->get("order_by",""))
            ->searchByTitle($request->get("search",""))
            ->filter($request->get("filter"),"");
        return new MovieCollection($query->paginate(10));
    }


    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(MovieRequest $request)
    {
        $validated = $request->validated();
        /**
         * @var Movie $movie
         */
        $movie = $this->movies->create(collect($validated)->except(['image'])->toArray());
        $movieImage = [];
        /**
         * @var UploadedFile $uploadImage
         */
        foreach ($validated['image'] as $uploadImage){
            $filename = sprintf("%s.%s",uniqid(),$uploadImage->getClientOriginalExtension());
            $uploadImage->move(storage_path("app/images"), $filename);
            $movieImage[] = new MovieImage([
                'path' => "images/".$filename
            ]);
        }
        $movie->images()->saveMany($movieImage);

        return new \App\Http\Resources\Movie($movie);
    }


    public function show($id)
    {
        return new \App\Http\Resources\Movie($this->movies->find($id));
    }



    public function update(MovieRequest $request, $id)
    {
        $movie = $this->movies->findOrFail($id);
        $validated = $request->validated();
        $movie->update($validated);
        return new \App\Http\Resources\Movie($movie);
    }


    public function destroy($id)
    {
        $this->movies->without("images")->findOrFail($id)->delete();

    }
}
