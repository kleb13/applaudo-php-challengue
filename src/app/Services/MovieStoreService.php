<?php


namespace App\Services;


use App\Contracts\MovieStore;
use App\Contracts\NesTransactionResult;
use App\Contracts\OkTransanctionResult;
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
            return new NesTransactionResult();
        }

        return new OkTransanctionResult();
    }

    public function rent(Movie $movie): TransactionResult
    {
        return new OkTransanctionResult();
    }

    public function return(Movie $movie): TransactionResult
    {
        return new OkTransanctionResult();
    }
}
