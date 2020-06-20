<?php


namespace App\Contracts;


class OkTransanctionResult extends TransactionResult
{
    public function __construct()
    {
        parent::__construct(true, "Ok");
    }
}
