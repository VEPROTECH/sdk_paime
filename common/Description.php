<?php
/**
 * Created by PhpStorm.
 * User: Verbeck DEGBESSE
 * Date: 15/10/2018
 * Time: 16:43
 */

class Description
{
 private $describe;

    /**
     * @return mixed
     */
    public function getDescribe()
    {
        return $this->describe;
    }

    /**
     * @param mixed $describe
     */
    public function setDescribe($describe)
    {
        $this->describe = $describe;
    }

}