<?php
/**
 * Created by PhpStorm.
 * User: Verbeck DEGBESSE
 * Date: 15/10/2018
 * Time: 16:46
 */

class Config
{
 private $apikey;
 private $mode;
 private $urlSuccess;
 private $urlReturn;
 private $intent;

    /**
     * @return mixed
     */
    public function getIntent()
    {
        return $this->intent;
    }

    /**
     * @param mixed $intent
     */
    public function setIntent($intent)
    {
        $this->intent = $intent;
    }


    /**
     * @return mixed
     */
    public function getApikey()
    {
        return $this->apikey;
    }

    /**
     * @param mixed $apikey
     */
    public function setApikey($apikey)
    {
        $this->apikey = $apikey;
    }

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return mixed
     */
    public function getUrlSuccess()
    {
        return $this->urlSuccess;
    }

    /**
     * @param mixed $urlSuccess
     */
    public function setUrlSuccess($urlSuccess)
    {
        $this->urlSuccess = $urlSuccess;
    }

    /**
     * @return mixed
     */
    public function getUrlReturn()
    {
        return $this->urlReturn;
    }

    /**
     * @param mixed $urlReturn
     */
    public function setUrlReturn($urlReturn)
    {
        $this->urlReturn = $urlReturn;
    }


}