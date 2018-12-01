<?php
/**
 * Created by PhpStorm.
 * User: Verbeck DEGBESSE
 * Date: 15/10/2018
 * Time: 16:35
 */

class Amount
{
 private $subtotal;
 private $tax;
 private $frais;
 private $currency;

    /**
     * @return mixed
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * @param mixed $subtotal
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
    }

    /**
     * @return mixed
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param mixed $tax
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
    }

    /**
     * @return mixed
     */
    public function getFrais()
    {
        return $this->frais;
    }

    /**
     * @param mixed $frais
     */
    public function setFrais($frais)
    {
        $this->frais = $frais;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }


}