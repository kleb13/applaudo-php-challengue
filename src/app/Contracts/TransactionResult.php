<?php


namespace App\Contracts;


class TransactionResult implements \JsonSerializable
{
    /**
     * @var boolean
     */
    protected $success;
    /**
     * @var string $message
     */
    protected $message;

    /**
     * TransactionResult constructor.
     * @param bool $sucess
     * @param string $message
     */
    public function __construct(bool $success, string $message)
    {
        $this->success = $success;
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function wasSuccess():bool
    {
        return $this->success;
    }

    /**
     * @return string
     */
    public function getMessage() :string
    {
        return $this->message;
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return ["message" =>$this->message];
    }
}
