<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait JsonRespondController
{
    /**
     * @var int
     */
    protected $httpStatusCode = 200;

    /**
     * @var int
     */
    protected $errorCode;

    /**
     * Sends a response unauthorized (401) to the request
     * Error cod = 42
     *
     * @param string $message
     * @return JsonResponse
     */
    public function respondUnauthorized($message = null)
    {
        return $this->setHTTPStatusCode(401)
            ->setErrorCode(42)
            ->respondWithError($message);
    }

    /**
     * Set Http status cod
     *
     * @param int $statusCode
     * @return self
     */
    public function setHTTPStatusCode($statusCode)
    {
        $this->httpStatusCode = $statusCode;

        return $this;
    }

    /**
     * Get HTTP status code fo the response
     *
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Set error cod of the response
     *
     * @param int $errorCode
     * @return self
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Get Error code fo the response
     *
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function respondWithError($message = null)
    {
        return $this->respond([
            'errors' => [
                'message' => $message ?? config('api.error_codes' . $this->getErrorCode()),
                'error_code' => $this->getErrorCode(),
            ],
        ]);
    }

    /**
     * Sends a Jon to the consumer
     *
     * @param array $data
     * @param array @headers
     * @return JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getHTTPStatusCode(), $headers);
    }
}