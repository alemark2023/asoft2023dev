<?php

namespace App\CoreFacturalo\WS\Response;

/**
 * Class BaseResult.
 */
class BaseResult
{
    /**
     * @var bool
     */
    protected $success;

    /**
     * @var Error
     */
    protected $error;

    /**
     * BaseResult constructor.
     */
    public function __construct()
    {
        $this->success = false;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param bool $success
     *
     * @return $this
     */
    public function setSuccess($success)
    {
        $this->success = $success;

        return $this;
    }

    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param Error $error
     *
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }
}
