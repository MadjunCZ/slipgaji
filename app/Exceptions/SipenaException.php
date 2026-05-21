<?php

namespace App\Exceptions;

use Exception;

class SipenaException extends Exception
{
    protected string $errorCode;
    protected array $context;

    public function __construct(
        string $message = 'Terjadi kesalahan',
        string $errorCode = 'UNKNOWN_ERROR',
        array $context = [],
        int $httpCode = 500
    ) {
        parent::__construct($message, $httpCode);
        $this->errorCode = $errorCode;
        $this->context = $context;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'error_code' => $this->errorCode,
            ], $this->getCode() ?: 500);
        }

        return back()->with('error', $this->getMessage());
    }
}
