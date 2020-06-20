<?php


namespace App\Contracts;


use App\Models\Movie;

interface  MovieStore
{
    public function buy(Movie $movie):TransactionResult;

    public function rent(Movie $movie):TransactionResult;

    public function return(Movie $movie):TransactionResult;
}
