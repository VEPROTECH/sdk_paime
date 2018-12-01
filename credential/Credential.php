<?php
/**
 * Created by PhpStorm.
 * User: Verbeck DEGBESSE
 * Date: 27/09/2018
 * Time: 15:07
 */

class Credential{
 private $app_key;
 private $server_key;

    /**
     * @return mixed
     */
    public function getAppKey()
    {
        return $this->app_key;
    }

    /**
     * @param mixed $app_key
     */
    public function setAppKey($app_key)
    {
        $this->app_key = $app_key;
    }

    /**
     * @return mixed
     */
    public function getServerKey()
    {
        return $this->server_key;
    }

    /**
     * @param mixed $server_key
     */
    public function setServerKey($server_key)
    {
        $this->server_key = $server_key;
    }



}