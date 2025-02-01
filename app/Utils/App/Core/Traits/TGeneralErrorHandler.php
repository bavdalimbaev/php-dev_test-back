<?php

namespace App\Utils\App\Core\Traits;

trait TGeneralErrorHandler
{
    private $errorMessage = 'Something went wrong,contact to administration';
    private $errorData = [];
    private $errorStatus = 500;

    protected function setError($message, $errorData, $errorStatus)
    {
        $this->setErrorData($errorData);
        $this->setErrorMessage($message);
        $this->setErrorStatus($errorStatus);

        return false;
    }

    public function getError()
    {
        return [
            $this->getErrorMessage(),
            $this->getErrorData(),
            $this->getErrorStatus()
        ];
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage)
    {
        if (is_string($errorMessage))
            $this->errorMessage = $errorMessage;
    }

    public function getErrorStatus()
    {
        return $this->errorStatus;
    }

    public function setErrorStatus(?int $errorStatus)
    {
        if ( $errorStatus )
            $this->errorStatus = $errorStatus;
    }

    public function getErrorData()
    {
        return $this->errorData;
    }

    public function setErrorData($errorData)
    {
        $this->errorData = $errorData;
    }
}
