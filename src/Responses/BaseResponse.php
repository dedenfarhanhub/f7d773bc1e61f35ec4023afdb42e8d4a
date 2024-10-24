<?php

namespace App\Responses;

class BaseResponse
{
    public int $code;
    public string $message;
    public array $errors;
    public mixed $data;

    /**
     * BaseResponse constructor.
     *
     * @param int $code HTTP status code.
     * @param string $message Response message.
     * @param array $errors Array of errors, if any.
     * @param mixed|null $data Response data.
     */
    public function __construct(int $code, string $message, array $errors = [], mixed $data = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->errors = $errors;
        $this->data = $data;
    }
}