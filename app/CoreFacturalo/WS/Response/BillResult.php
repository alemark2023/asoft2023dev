<?php

namespace App\CoreFacturalo\WS\Response;

/**
 * Class BillResult.
 */
class BillResult extends BaseResult
{
    /**
     * @var string
     */
    protected $cdrZip;

    /**
     * @var CdrResponse
     */
    protected $cdrResponse;

    /**
     * @return string
     */
    public function getCdrZip()
    {
        return $this->cdrZip;
    }

    /**
     * @param string $cdrZip
     *
     * @return $this
     */
    public function setCdrZip($cdrZip)
    {
        $this->cdrZip = $cdrZip;

        return $this;
    }

    /**
     * @return CdrResponse
     */
    public function getCdrResponse()
    {
        return $this->cdrResponse;
    }

    /**
     * @param CdrResponse $cdrResponse
     *
     * @return $this
     */
    public function setCdrResponse($cdrResponse)
    {
        $this->cdrResponse = $cdrResponse;

        return $this;
    }
}
