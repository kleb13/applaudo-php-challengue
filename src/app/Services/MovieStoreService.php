<?php


namespace App\Services;


use App\Contracts\MovieStore;
use App\Contracts\TransactionResult;
use App\Models\Movie;
use Illuminate\Database\DatabaseManager as DB;

class MovieStoreService implements MovieStore
{
    protected $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    /**
     * @param Movie $movie
     * @return TransactionResult
     * @throws \Throwable
     */
    public function buy(Movie $movie) : TransactionResult
    {
        if($movie->stock <= 0) {
            return new TransactionResult(false, "Not enough stock");
        }

        return new TransactionResult(true,"Ok");
    }

    public function rent(Movie $movie): TransactionResult
    {
        return new TransactionResult(true,"Ok");
    }

    public function return(Movie $movie): TransactionResult
    {
        return new TransactionResult(true,"Ok");
    }
}
