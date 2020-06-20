<?php


namespace App\Services;


use Illuminate\Contracts\Auth\Factory as Auth;
use App\Contracts\{MovieStore,NesTransactionResult,OkTransanctionResult,TransactionResult};
use App\Models\{Movie,MovieTransaction};
use Illuminate\Database\DatabaseManager as DB;

class MovieStoreService implements MovieStore
{
    protected $db;
    protected $auth;
    public function __construct(DB $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
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

        try {
            $this->db->beginTransaction();
            $movie->stock -= 1;
            if($movie->stock === 0 && $movie->availability) {
                $movie->availability = false;
            }
            $movie->save();

            $movie->transactions()->save(new MovieTransaction([
                "reason" => MovieTransaction::BUY,
                "amount" => $movie->sale_price,
                "user_id" => $this->auth->guard()->user()->getAuthIdentifier()
            ]));

            $this->db->commit();
            return new OkTransanctionResult();
        }catch (\Exception $exception){
            $this->db->rollBack();
            return new TransactionResult(false,"something went wrong");
        }
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
