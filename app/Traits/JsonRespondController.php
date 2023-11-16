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
                'message' => $message ?? config('api.error_codes.'.$this->getErrorCode()),
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

    /**
     * Sends a aresponse of invalid query (http 500) to the request.
     * Error code = 40
     * 
     * @param string $message
     * @return JsonResponse
     */
    public function respondInvalidQuery($message = null)
    {
        return $this->setHttpStatusCode(500)
                    ->setErrorCode(40)
                    ->respondWithError($message);
        
    }

    /**
     * Sends error when the query didn't have the right parameters for creating an object
     * Error code = 33
     * 
     * @param string $message
     * @return JsonResponse
     */
    public function respondNotTheRightParameters($message = null)
    {
        return $this->setHttpStatusCode(500)
                    ->etErrorCodes(33)
                    ->respondWithError($message);
    }

    /**
     * Sends a response not found (404) to the request
     * Error code = 31
     * 
     * @return JsonResponse
     */
    public function respondNotFound()
    {
        return $this->setHttpStatusCode(404)
                    ->setErrorCode(31)
                    ->respondWithError();
    }

    /**
     * Sends a response indicating the object has been deleted and id of deleted object
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function respondObjectDeleted($id)
    {
        return $this->respond([
                'deleted' => true,
                'id' => $id
        ]);
    }
}