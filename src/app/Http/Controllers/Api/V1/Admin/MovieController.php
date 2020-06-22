<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieRequest;
use App\Http\Resources\MovieCollection;
use App\Models\MovieImage;
use App\Models\Movie;
use App\Scopes\AvailabilityScope;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class MovieController
 * Only users with admin access can have perform this actions
 * @package App\Http\Controllers\Api\V1\Admin
 */
class MovieController extends Controller
{

    protected $movies;

    public function __construct(Movie $movies)
    {
        // Remove Availability scope for the admin, needs to see every movie despite its availability
        $this->movies = $movies->withoutGlobalScope(AvailabilityScope::class);
    }

    /**
     * Get the movies availabe and unavailable, but you can filter by:
     * - availabe
     * - unavailable
     * You can search by movie title with the field search.
     * And paginate with the field page
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
     * Create a movie expects at least one image
     *
     * @param MovieRequest $request
     * @return \App\Http\Resources\Movie
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


    /**
     * Get the detail for a specific movie
     * return http status code 404 if the movie does not exist
     * @param $id
     * @return \App\Http\Resources\Movie
     */
    public function show($id)
    {
        return new \App\Http\Resources\Movie($this->movies->findOrFail($id));
    }


    /**
     * Update a movie
     * Return 404 if the movie does not exists
     * @param MovieRequest $request
     * @param $id
     * @return \App\Http\Resources\Movie
     */
    public function update(MovieRequest $request, $id)
    {
        $movie = $this->movies->findOrFail($id);
        $validated = $request->validated();
        $movie->update($validated);
        return new \App\Http\Resources\Movie($movie);
    }

    /**
     * Delete a movie
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $this->movies->without("images")->findOrFail($id)->delete();

        return (new JsonResponse())->setStatusCode(204);
    }
}
