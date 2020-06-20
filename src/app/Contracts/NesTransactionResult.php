<?php


namespace App\Contracts;


class NesTransactionResult extends TransactionResult
{
    public function __construct()
    {
        parent::__construct(false, "Not enough stock");
    }

}
